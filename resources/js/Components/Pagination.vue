<template>
    <div class="flex items-center justify-between border-t border-gray-200  px-4 py-3 sm:px-6">
        <!-- Mobile: Prev / Next -->
        <div class="flex flex-1 justify-between sm:hidden">
            <component
                :is="previousUrl ? Link : 'span'"
                v-bind="previousUrl ? { href: previousUrl, preserveScroll: true } : {}"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium"
                :class="previousUrl ? 'text-gray-700 hover:bg-gray-50' : 'cursor-not-allowed text-gray-300'"
            >
                Previous
            </component>

            <component
                :is="nextUrl ? Link : 'span'"
                v-bind="nextUrl ? { href: nextUrl, preserveScroll: true } : {}"
                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium"
                :class="nextUrl ? 'text-gray-700 hover:bg-gray-50' : 'cursor-not-allowed text-gray-300'"
            >
                Next
            </component>
        </div>

        <!-- Desktop: summary + numbered links -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div v-if="meta">
                <p class="text-sm text-gray-700">
                Showing <span class="font-medium">{{ meta.from }}</span>
                to <span class="font-medium">{{ meta.to }}</span>
                of <span class="font-medium">{{ meta.total }}</span> results
                </p>
            </div>

            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md bg-white shadow-xs" aria-label="Pagination">
                <component
                    v-for="(link, i) in meta?.links || []"
                    :key="i"
                    :is="link.url ? Link : 'span'"
                    v-bind="link.url ? { href: link.url, preserveScroll: true } : {}"
                    class="relative inline-flex items-center first-of-type:rounded-l-md last-of-type:rounded-r-md px-3 py-2 ring-1 ring-inset"
                    :class="{
                    'z-10 bg-indigo-600 text-white ring-indigo-600': link.active,
                    'text-gray-900 hover:bg-gray-50 ring-gray-300': !link.active && link.url,
                    'cursor-default text-gray-400 bg-white ring-gray-300': !link.url
                    }"
                >
                    <!-- Label from Laravel includes «, », and numbers; render safely -->
                    <span v-html="link.label"></span>
                </component>
                </nav>
            </div>
        </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  meta: { type: Object, required: true }
})

// Laravel's links array: [0] = « Previous, [...pages...], [last] = Next »
// Those urls are null on first/last page — guard them.
const previousUrl = computed(() => props.meta?.links?.[0]?.url || null)
const nextUrl = computed(() => {
  const links = props.meta?.links || []
  return links.length ? links[links.length - 1]?.url || null : null
})
</script>
