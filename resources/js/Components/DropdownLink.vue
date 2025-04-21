<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:hover:bg-gray-300
        focus:outline-none dark:focus:bg-gray-800 transition duration-150 ease-in-out text-decoration-none"
    >
        <slot />
    </Link>
</template>
