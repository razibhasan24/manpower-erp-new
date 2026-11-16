#!/usr/bin/env node
/**
 * Static navigation / view / permission verifier.
 *
 * This project has no PHP in the authoring workspace, so this script gives
 * a repeatable check that does not need PHP or Composer:
 *   - every menu route name exists
 *   - every literal route() name used by a view/controller exists
 *   - every Blade view referenced by a controller exists
 *   - every Blade component used exists
 *   - every permission referenced by the menu/modules/route middleware exists
 *   - every icon referenced by config/views exists
 *   - duplicate route names are reported
 *
 * Run from the project root: node tools/verify-navigation.mjs
 */

import fs from 'node:fs';
import path from 'node:path';

const root = process.cwd();
const errors = [];
const warnings = [];
const read = (p) => fs.readFileSync(path.join(root, p), 'utf8');
const exists = (p) => fs.existsSync(path.join(root, p));
const rel = (p) => p.replace(root + path.sep, '').replaceAll('\\', '/');

function walk(dir, predicate, out = []) {
  const absolute = path.join(root, dir);
  if (!fs.existsSync(absolute)) return out;

  for (const entry of fs.readdirSync(absolute, { withFileTypes: true })) {
    const relative = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      if (!/node_modules|vendor|storage|\.git/.test(relative)) walk(relative, predicate, out);
    } else {
      const normalized = relative.replaceAll('\\', '/');
      if (predicate(normalized)) out.push(normalized);
    }
  }
  return out;
}

/* ------------------------------------------------------------------ routes */

const routeFiles = [
  ...walk('routes', (p) => p.endsWith('.php')),
];

const routeNames = new Map();
const routeNameOwners = new Map();

function addRouteName(name, file) {
  routeNames.set(name, file);
  if (!routeNameOwners.has(name)) routeNameOwners.set(name, []);
  routeNameOwners.get(name).push(file);
}

for (const file of routeFiles) {
  const source = read(file);
  const prefixes = [...source.matchAll(/->name\(\s*'([^']+\.)'\s*\)/g)].map((m) => m[1]);
  const prefix = prefixes.length ? prefixes[prefixes.length - 1] : '';

  const fullName = (name) => (prefix && !name.startsWith(prefix) ? prefix + name : name);

  for (const match of source.matchAll(/->name\(\s*'([^']+)'\s*\)/g)) {
    const name = match[1];
    if (name.endsWith('.')) continue;
    addRouteName(fullName(name), file);
  }

  for (const match of source.matchAll(/Route::resource\(\s*'([^']+)'[\s\S]*?;/g)) {
    const block = match[0];
    const base = fullName(match[1].replaceAll('/', '.'));
    const only = block.match(/->only\(\s*\[([^\]]*)\]/);
    const except = block.match(/->except\(\s*\[([^\]]*)\]/);
    const all = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
    let actions = all;
    if (only) actions = [...only[1].matchAll(/'([^']+)'/g)].map((m) => m[1]);
    if (except) {
      const excluded = new Set([...except[1].matchAll(/'([^']+)'/g)].map((m) => m[1]));
      actions = all.filter((action) => !excluded.has(action));
    }
    for (const action of actions) addRouteName(`${base}.${action}`, file);
  }
}

for (const [name, owners] of routeNameOwners) {
  if (owners.length > 1) {
    const unique = [...new Set(owners)];
    if (unique.length > 1) warnings.push(`Duplicate route name "${name}" in ${unique.join(', ')}`);
  }
}

/* -------------------------------------------------------------------- menu */

const menu = read('config/menu.php');
const moduleConfig = read('config/modules.php');
const seeder = read('database/seeders/RolePermissionSeeder.php');

const menuRoutes = [...menu.matchAll(/'route'\s*=>\s*'([^']+)'/g)].map((m) => m[1]);
for (const route of new Set(menuRoutes)) {
  if (!routeNames.has(route)) errors.push(`Menu route "${route}" is not defined in any route file.`);
}

const moduleKeys = [...moduleConfig.matchAll(/^    '([^']+)' => \[/gm)].map((m) => m[1]);
const moduleRefs = [...menu.matchAll(/'route'\s*=>\s*'modules\.show',\s*'params'\s*=>\s*\['module'\s*=>\s*'([^']+)'\]/g)].map((m) => m[1]);
for (const key of new Set(moduleRefs)) {
  if (!moduleKeys.includes(key)) errors.push(`Menu references unknown development module "${key}".`);
}
for (const key of moduleKeys) {
  if (!moduleRefs.includes(key)) warnings.push(`Development module "${key}" is defined but not linked from the menu.`);
}

const relatedRoutes = [...moduleConfig.matchAll(/'related_route'\s*=>\s*'([^']+)'/g)].map((m) => m[1]);
for (const route of new Set(relatedRoutes)) {
  if (!routeNames.has(route)) errors.push(`Development module related route "${route}" is not defined.`);
}

/* ------------------------------------------------------------- permissions */

const moduleBlock = seeder.slice(seeder.indexOf('MODULE_ACTIONS'), seeder.indexOf('ROLE_PERMISSIONS'));
const seededPermissions = new Set();
for (const match of moduleBlock.matchAll(/'([a-z0-9_\-]+)'\s*=>\s*\[([^\]]*)\]/g)) {
  for (const action of match[2].matchAll(/'([a-z0-9_\-]+)'/g)) {
    seededPermissions.add(`${match[1]}.${action[1]}`);
  }
}

const roleBlock = seeder.slice(seeder.indexOf('ROLE_PERMISSIONS'), seeder.indexOf('public function run'));
const seededRoles = new Set([...roleBlock.matchAll(/^        '([^']+)' =>/gm)].map((m) => m[1]));

const permissionRefs = new Set();
for (const source of [menu, moduleConfig]) {
  for (const match of source.matchAll(/'permission'\s*=>\s*'([^']+)'/g)) permissionRefs.add(match[1]);
  for (const match of source.matchAll(/'permission'\s*=>\s*\[([^\]]+)\]/g)) {
    for (const one of match[1].matchAll(/'([^']+)'/g)) permissionRefs.add(one[1]);
  }
}
for (const file of routeFiles) {
  const source = read(file);
  for (const match of source.matchAll(/permission:([a-zA-Z0-9_.\-|]+)/g)) {
    for (const permission of match[1].split('|')) permissionRefs.add(permission);
  }
}

for (const permission of permissionRefs) {
  if (!seededPermissions.has(permission)) errors.push(`Permission "${permission}" is referenced but not seeded.`);
}

for (const file of routeFiles) {
  const source = read(file);
  for (const match of source.matchAll(/role:([^'")]+)/g)) {
    for (const role of match[1].split('|')) {
      if (!seededRoles.has(role.trim())) warnings.push(`Route middleware references unknown role "${role.trim()}" in ${file}.`);
    }
  }
}

/* ------------------------------------------------------------- controllers */

for (const file of routeFiles) {
  const source = read(file);
  for (const match of source.matchAll(/([A-Z][A-Za-z0-9_]+Controller)::class/g)) {
    const className = match[1];
    const found = walk('app/Http/Controllers', (p) => p.endsWith(`/${className}.php`)).length > 0;
    if (!found) errors.push(`Route file ${file} references missing controller ${className}.`);
  }
}

/* ------------------------------------------------------------------- views */

const bladeViews = new Set(
  walk('resources/views', (p) => p.endsWith('.blade.php')).map((p) =>
    p.replace('resources/views/', '').replace(/\\/g, '/').replace(/\.blade\.php$/, '').replaceAll('/', '.')
  )
);

for (const file of walk('app/Http/Controllers', (p) => p.endsWith('.php'))) {
  const source = read(file);
  for (const match of source.matchAll(/view\(\s*'([^']+)'/g)) {
    if (!bladeViews.has(match[1])) errors.push(`Controller ${file} returns missing view "${match[1]}".`);
  }
}

for (const file of walk('resources/views', (p) => p.endsWith('.blade.php'))) {
  const source = read(file);
  for (const match of source.matchAll(/@(?:extends|include|component)\(\s*'([^']+)'/g)) {
    if (!bladeViews.has(match[1])) errors.push(`View ${file} references missing view "${match[1]}".`);
  }

  for (const match of source.matchAll(/<x-([a-z0-9-]+)(?=[\s/>]|:)/g)) {
    const component = match[1];
    if (component === 'slot') continue;
    const view = `resources/views/components/${component}.blade.php`;
    const classFile = walk('app/View/Components', (p) => p.endsWith(`${component}.php`)).length > 0;
    if (!exists(view) && !classFile) errors.push(`View ${file} uses missing component <x-${component}>.`);
  }
}

/* ------------------------------------------------------------------- icons */

const iconConfig = read('config/icons.php');
const iconNames = new Set([...iconConfig.matchAll(/^    '([^']+)' =>/gm)].map((m) => m[1]));
const iconRefs = new Set();

for (const match of menu.matchAll(/'icon'\s*=>\s*'([^']+)'/g)) iconRefs.add(match[1]);
for (const source of [moduleConfig]) {
  for (const match of source.matchAll(/'icon'\s*=>\s*'([^']+)'/g)) iconRefs.add(match[1]);
}
for (const file of walk('resources/views', (p) => p.endsWith('.blade.php'))) {
  const source = read(file);
  for (const match of source.matchAll(/<x-icon[^>]*\bname="([^"]+)"/g)) {
    if (!match[1].startsWith('$')) iconRefs.add(match[1]);
  }
}

for (const icon of iconRefs) {
  if (!iconNames.has(icon)) errors.push(`Icon "${icon}" is referenced but missing from config/icons.php.`);
}

/* -------------------------------------------------------------- route refs */

const routeRefs = new Map();
for (const file of [
  ...walk('app/Http/Controllers', (p) => p.endsWith('.php')),
  ...walk('resources/views', (p) => p.endsWith('.blade.php')),
]) {
  const source = read(file);
  for (const match of source.matchAll(/route\(\s*'([^']+)'/g)) {
    if (!routeRefs.has(match[1])) routeRefs.set(match[1], []);
    routeRefs.get(match[1]).push(file);
  }
}

for (const [name, files] of routeRefs) {
  if (!routeNames.has(name)) {
    errors.push(`route('${name}') is used in ${[...new Set(files)].slice(0, 3).join(', ')} but the name is not defined.`);
  }
}

/* ------------------------------------------------------------------ report */

console.log('Navigation verifier');
console.log('-------------------');
console.log(`Route names:        ${routeNames.size}`);
console.log(`Menu destinations:  ${new Set(menuRoutes).size}`);
console.log(`Menu routes:        ${menuRoutes.length}`);
console.log(`Development pages:  ${new Set(moduleRefs).size}`);
console.log(`Permissions:        ${permissionRefs.size}`);
console.log(`Views:              ${bladeViews.size}`);
console.log(`Icons:              ${iconNames.size}`);
console.log(`route() references: ${routeRefs.size}`);

if (warnings.length) {
  console.log('\nWarnings:');
  for (const warning of warnings) console.log(`  - ${warning}`);
}

if (errors.length) {
  console.log('\nErrors:');
  for (const error of errors) console.log(`  - ${error}`);
  process.exitCode = 1;
} else {
  console.log('\nOK: no missing routes, views, components, permissions or icons found.');
}
