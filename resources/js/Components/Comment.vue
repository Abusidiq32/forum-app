<template>
    <div class="sm:flex">
        <div class="mb-4 flex-shrink-0 sm:mb-0 sm:mr-4">
            <img :src="comment.user.profile_photo_url" class="h-10 w-10 rounded-full" alt=""/>
        </div>
        
        <div>
            <p class="mt-1">{{ comment.body }}</p>
            <span class="first-letter:uppercase block pt-1 text-xs text-gray-600">by {{ comment.user.name }} {{ relativeDate(comment.created_at) }} ago</span>
        </div>
    </div>
</template>

<script setup>
    defineProps(['comment']);
    import { relativeDate } from '@/Utilities/date';
</script>