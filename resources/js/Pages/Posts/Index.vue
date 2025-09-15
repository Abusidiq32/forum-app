<template>
    <Head title="Posts" />

    <AppLayout>
        <Container> 
            <ul class="divide-y">
                 <li v-for="post in posts.data" :key="post.id" class="">
                    <Link :href="route('posts.show', post.id)" class="group block px-2 py-4">
                        <span class="font-bold text-lg group-hover:text-indigo-500"> {{ post.title }} </span>
                        <span class="block pt-1 text-sm text-gray-600">{{ formattedDate(post) }} ago by {{ post.user.name }}</span>
                    </Link>
                 </li>
            </ul>

            <Pagination :meta="posts.meta" />
        </Container>
    </AppLayout>

</template>


<script setup>
    import Container from '@/Components/Container.vue';
    import Pagination from '@/Components/Pagination.vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { formatDistance, parseISO } from 'date-fns'
    import { Head, Link } from '@inertiajs/vue3';

    const props = defineProps(['posts']);
    const formattedDate = (post) => {
        return formatDistance(parseISO(post.created_at), new Date())
    }
</script>