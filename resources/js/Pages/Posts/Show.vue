<template>
    <AppLayout :title="post.title">
        <Container>
            <h1 class="text-2xl font-bold">{{ post.title }}</h1>
            <span class="block mt-1 text-sm text-gray-600">{{ formattedDate }} ago by {{ post.user.name }}</span>
    
            <article class="mt-6">
                <pre class="whitespace-pre-wrap font-sans">
                    {{ post.body }}
                </pre>
            </article>

            <div class="mt-10">
                <h2 class="text-xl font-semibold">Comments</h2>
                <form v-if="$page.props.auth.user" @submit.prevent="addComment" class="mt-4">
                    <div>
                        <TextArea name="body" id="body" v-model="commentForm.body" rows="4" placeholder="Add your comment..."/>
                        <InputError :message="commentForm.errors.body" class="mt-2" v-if="commentForm.errors.body" />
                    </div>
                    <PrimaryButton type="submit" class="mt-2" :disabled="commentForm.processing">Add Comment</PrimaryButton>
                </form>

                <ul class="divide-y mt-4">
                    <li v-for="comment in comments.data" :key="comment.id" class=" px-2 py-4">
                        <Comment @delete="deleteComment" :comment="comment" />
                    </li>
                </ul>

                <Pagination :meta="comments.meta" class="mt-4" />
            </div>
        </Container>
    </AppLayout>

</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import Comment from '@/Components/Comment.vue';
import { computed } from 'vue';
import { relativeDate } from '@/Utilities/date';
import { router, useForm } from '@inertiajs/vue3';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';


const props = defineProps(['post', 'comments']);
const formattedDate = computed(() => relativeDate(props.post.created_at));

const commentForm = useForm({
    body: '',
});
const addComment = () => commentForm.post(route('posts.comment.store', props.post.id), {
    preserveScroll: true,
    onSuccess: () => commentForm.reset('body'),
});

const deleteComment = (commentId) => router.delete(route('comments.destroy', {comment: commentId, page: props.comments.meta.current_page}), {
    preserveScroll: true,
});

</script>