<template>
    <div class="sm:flex">
        <div class="mb-4 flex-shrink-0 sm:mb-0 sm:mr-4">
            <img :src="comment.user.profile_photo_url" class="h-10 w-10 rounded-full" alt=""/>
        </div>
        
        <div>
            <p class="mt-1 break-all">{{ comment.body }}</p>
            <span class="first-letter:uppercase block pt-1 text-xs text-gray-600">by {{ comment.user.name }} {{ relativeDate(comment.created_at) }} ago</span>
            <div class="mt-1">
                <form v-if="comment.can?.delete" @submit.prevent="deleteComment">
                    <button>Delete</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    const props = defineProps(['comment']);
    import { relativeDate } from '@/Utilities/date';
    import { router, usePage } from "@inertiajs/vue3";
    import { computed } from 'vue';
    
    const deleteComment = () => router.delete(route('comments.destroy', props.comment.id), {
        preserveScroll: true,
    });

    // Used to display the delete button only if the comment belongs to the authenticated user
    // 'v-if="cantDelete"'
    // const cantDelete = computed(() => props.comment.user.id === usePage().props.auth.user?.id);
</script>