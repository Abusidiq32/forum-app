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
                <form v-if="$page.props.auth.user" @submit.prevent="commentIdBeingEdited ? openUpdateModal() : addComment()" class="mt-4">
                    <div>
                        <TextArea ref="commentTextAreaRef" name="body" id="body" v-model="commentForm.body" rows="4" placeholder="Add your comment..."/>
                        <InputError :message="commentForm.errors.body" class="mt-2" v-if="commentForm.errors.body" />
                    </div>
                    <PrimaryButton type="submit" class="mt-2" :disabled="commentForm.processing" v-text="commentIdBeingEdited ? 'Update Comment' : 'Add Comment'"></PrimaryButton>
                    <SecondaryButton v-if="commentIdBeingEdited" @click="cancelEditComment" class="ml-2">Cancel</SecondaryButton>
                </form>

                <ul class="divide-y mt-4">
                    <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4">
                    <Comment @edit="editComment" @delete="openDeleteModal(comment.id)" :comment="comment" />
                    </li>
                </ul>

                <Pagination :meta="comments.meta" class="mt-4" />
            </div>

            <!-- Update Confirmation modal -->
            <ConfirmationModalWrapper
                v-model="showUpdateModal"
                title="Update Comment"
                message="Are you sure you want to update this comment?"
                confirmText="Update"
                cancelText="Cancel"
                :processing="updating"
                @confirm="confirmUpdate"
            />
            
            <!-- Delete Confirmation modal -->
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
    // Imports
    import { computed, ref } from 'vue';
    import { router, useForm } from '@inertiajs/vue3';

    import AppLayout from '@/Layouts/AppLayout.vue';
    import Container from '@/Components/Container.vue';
    import Pagination from '@/Components/Pagination.vue';
    import Comment from '@/Components/Comment.vue';
    import TextArea from '@/Components/TextArea.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputError from '@/Components/InputError.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import ConfirmationModalWrapper from '@/Components/ConfirmationModalWrapper.vue';

    import { relativeDate } from '@/Utilities/date';

    // Props
    const props = defineProps(['post', 'comments']);

    // Computed Values
    const formattedDate = computed(() => relativeDate(props.post.created_at));
    const commentBeingEdit = computed(() => props.comments.data.find(comment => comment.id === commentIdBeingEdited.value));

    // Refs annd States
    const commentForm = useForm({ body: '',});

    const commentTextAreaRef = ref(null);
    const commentIdBeingEdited = ref(null);
    const originalBody = ref('')

    const showUpdateModal = ref(false)
    const updating = ref(false)

    // Delete modal State
    const showDeleteModal = ref(false)
    const deleting = ref(false)
    const commentIdPendingDelete = ref(null)

    // Methods - Editing
    const editComment = (commentId) => {
        commentIdBeingEdited.value = commentId
        commentForm.body = commentBeingEdit.value?.body ?? ''
        originalBody.value = commentForm.body
        commentTextAreaRef.value?.focus()
    }

    // Methods - Cancel Edit
    const cancelEditComment = () => {
        commentIdBeingEdited.value = null;
        commentForm.reset('body');
    }

    // Methods - Adding, Updating
    const addComment = () => commentForm.post(route('posts.comment.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset('body'),
    });
    
    const openUpdateModal = () => {
        showUpdateModal.value = true
    }

    const confirmUpdate = () => {
        updating.value = true
        commentForm.put(
            route('comments.update', {
            comment: commentIdBeingEdited.value,
            page: props.comments.meta.current_page,
            }),
            {
            preserveScroll: true,
            onSuccess: () => {
                commentIdBeingEdited.value = null
                commentForm.reset('body')
            },
            onFinish: () => {
                updating.value = false
                showUpdateModal.value = false
            },
            }
        )
    }

    // Methods - Deleting
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