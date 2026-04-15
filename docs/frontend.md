# Frontend — Inertia.js + Vue 3

## Overview

The frontend uses Inertia.js to bridge Laravel controllers and Vue components without building a separate API. The server controls navigation and data; Vue handles rendering.

There is no Vue Router. Page transitions are handled by Inertia itself.

---

## Entry Point — `resources/js/app.js`

```js
import { createInertiaApp } from '@inertiajs/vue3'

const modulePages = import.meta.glob('../../app/Modules/**/Resources/Pages/**/*.vue')

createInertiaApp({
    resolve: (name) => {
        const [module, ...rest] = name.split('/')
        const page = rest.join('/')
        const key = `../../app/Modules/${module}/Resources/Pages/${page}.vue`
        return modulePages[key]()
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(createPinia())
            .mount(el)
    },
})
```

**How the resolver works:**

When a controller calls `Inertia::render('Car/Index')`, Inertia sends the string `Car/Index` to the frontend. The resolver splits it:
- `module` = `Car`
- `page` = `Index`
- Looks up: `../../app/Modules/Car/Resources/Pages/Index.vue`

This allows each module to own its Vue pages.

---

## Creating a Page

Pages live inside the module they belong to:

```
app/Modules/Car/Resources/Pages/Index.vue
```

Basic page structure:

```vue
<script setup>
import { Head, Link } from '@inertiajs/vue3'

// Props come directly from Inertia::render() in the Action
const props = defineProps({
    cars: Object,   // LengthAwarePaginator serialized as { data, links, meta }
    flash: Object,  // Shared globally by HandleInertiaRequests
})
</script>

<template>
    <Head title="Cars" />

    <div v-if="flash?.msg">{{ flash.msg }}</div>

    <div v-for="car in cars.data" :key="car.id">
        {{ car.brand }}
        <Link :href="`/cars/${car.id}`">Edit</Link>
    </div>
</template>
```

---

## Key Inertia Components

### `<Head>`
Sets the page `<title>` without a full page reload.

```vue
<Head title="Cars" />
```

### `<Link>`
Replaces `<a href>`. Performs SPA navigation via Inertia (no full reload).

```vue
<Link href="/cars">Back to list</Link>
<Link :href="`/cars/${car.id}`">Edit</Link>
```

For forms, use the `method` prop:

```vue
<Link :href="`/cars/${car.id}`" method="delete" as="button">Delete</Link>
```

---

## Flash Messages

Flash messages are shared globally in `HandleInertiaRequests::share()`:

```php
'flash' => [
    'msg'  => $request->session()->get('msg'),
    'type' => $request->session()->get('type'),
],
```

This means every page receives `flash` automatically. Set them from any Action using:

```php
return redirect()->route('cars.index')
    ->with(['msg' => 'Car deleted!', 'type' => 'success']);
```

In the Vue page, read from `defineProps`:

```vue
const props = defineProps({ flash: Object })
```

```html
<div v-if="flash?.msg" :class="flash.type === 'success' ? 'bg-green-100' : 'bg-red-100'">
    {{ flash.msg }}
</div>
```

---

## Pagination

When passing a `LengthAwarePaginator` from Laravel, Inertia serializes it automatically into:

```json
{
  "data": [...],
  "links": [
    { "url": null,          "label": "&laquo;",  "active": false },
    { "url": "/cars?page=1","label": "1",        "active": true  },
    { "url": "/cars?page=2","label": "2",        "active": false },
    { "url": null,          "label": "&raquo;",  "active": false }
  ],
  "meta": { "current_page": 1, "last_page": 4, ... }
}
```

Render it in Vue:

```vue
<Link
    v-for="link in cars.links"
    :key="link.label"
    :href="link.url ?? '#'"
    v-html="link.label"
    :class="[
        link.active ? 'bg-blue-600 text-white' : 'bg-white text-gray-600',
        !link.url ? 'opacity-40 pointer-events-none' : ''
    ]"
/>
```

---

## State Management — Pinia

Pinia is available for client-side state that doesn't come from the server (UI state, modals, etc.). For data that comes from the backend, prefer Inertia props directly — no need to duplicate server data into a store.

Create a store at `resources/js/stores/`:

```js
// resources/js/stores/useCarStore.js
import { defineStore } from 'pinia'

export const useCarStore = defineStore('car', {
    state: () => ({
        selectedId: null,
    }),
})
```
