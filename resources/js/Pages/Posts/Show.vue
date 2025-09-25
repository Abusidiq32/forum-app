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
                <form v-if="$page.props.auth.user" @submit.prevent=" () => commentIdBeingEdited ? updateComment() : addComment()" class="mt-4">
                    <div>
                        <TextArea ref="commentTextAreaRef" name="body" id="body" v-model="commentForm.body" rows="4" placeholder="Add your comment..."/>
                        <InputError :message="commentForm.errors.body" class="mt-2" v-if="commentForm.errors.body" />
                    </div>
                    <PrimaryButton type="submit" class="mt-2" :disabled="commentForm.processing" v-text="commentIdBeingEdited ? 'Update Comment' : 'Add Comment'"></PrimaryButton>
                    <SecondaryButton v-if="commentIdBeingEdited" @click="cancelEditComment" class="ml-2">Cancel</SecondaryButton>
                </form>
<!-- 
                <ul class="divide-y mt-4">
                    <li v-for="comment in comments.data" :key="comment.id" class=" px-2 py-4">
                        <Comment @edit="editComment" @delete="deleteComment" :comment="comment" />
                    </li>
                </ul> -->

                <ul class="divide-y mt-4">
                    <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4">
                    <Comment @edit="editComment" @delete="openDeleteModal(comment.id)" :comment="comment" />
                    </li>
                </ul>

                <Pagination :meta="comments.meta" class="mt-4" />
            </div>

            <!-- Confirmation modal -->
            <ConfirmationModalWrapper
                v-model="showDeleteModal"
                title="Delete Comment"
                message="Are you sure you want to delete this comment? This action cannot be undone."
                confirmText="Delete"
                cancelText="Cancel"
                :processing="deleting"
                @confirm="confirmDelete"
            />
        </Container>
    </AppLayout>

</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import Comment from '@/Components/Comment.vue';
import { computed, ref } from 'vue';
import { relativeDate } from '@/Utilities/date';
import { router, useForm } from '@inertiajs/vue3';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ConfirmationModalWrapper from '@/Components/ConfirmationModalWrapper.vue';


const props = defineProps(['post', 'comments']);
const formattedDate = computed(() => relativeDate(props.post.created_at));

const commentForm = useForm({
    body: '',
});

const commentTextAreaRef = ref(null);
const commentIdBeingEdited = ref(null);
const commentBeingEdit = computed(() => props.comments.data.find(comment => comment.id === commentIdBeingEdited.value));
const editComment = (commentId) => {
    commentIdBeingEdited.value = commentId;
    commentForm.body = commentBeingEdit.value?.body;
    commentTextAreaRef.value?.focus();

}

const cancelEditComment = () => {
    commentIdBeingEdited.value = null;
    commentForm.reset('body');
}

const addComment = () => commentForm.post(route('posts.comment.store', props.post.id), {
    preserveScroll: true,
    onSuccess: () => commentForm.reset('body'),
});

const updateComment = () => commentForm.put(route('comments.update', {
    comment: commentIdBeingEdited.value,
    page: props.comments.meta.current_page,
}), {
    preserveScroll: true, onSuccess: () => (commentIdBeingEdited.value = null, commentForm.reset('body')) })

// delete with modal
const showDeleteModal = ref(false)
const deleting = ref(false)
const commentIdPendingDelete = ref(null)

const openDeleteModal = (id) => {
  commentIdPendingDelete.value = id
  showDeleteModal.value = true
}

const confirmDelete = () => {
  if (!commentIdPendingDelete.value) return
  deleting.value = true
  router.delete(route('comments.destroy', { comment: commentIdPendingDelete.value, page: props.comments.meta.current_page }), {
    preserveScroll: true,
    onFinish: () => {
      deleting.value = false
      showDeleteModal.value = false
      commentIdPendingDelete.value = null
    }
  })
}

</script>