<template>
    <AppLayout title="Create Post">
        <Container>
            <h1 class="text-2xl font-bold">Create a Post</h1>
    
            <form @submit.prevent="submit" class="mt-6 space-y-6">
                <div>
                    <TextInput
                        id="title"
                        type="text"
                        name="title"
                        v-model="form.title"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Give it a greate title..."
                    />
                    <InputError :message="form.errors.title" class="mt-1" v-if="form.errors.title" />
                </div>
    
                <div>
                    <div class="mt-1">
                        <textarea
                            name="body"
                            id="body"
                            v-model="form.body"
                            rows="25"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Write the content of your post here..."
                        ></textarea>
                    </div>
                    <InputError :message="form.errors.body" class="mt-2" v-if="form.errors.body" />
                </div>
    
                <PrimaryButton type="submit" :disabled="form.processing">Create Post</PrimaryButton>

            </form>
        </Container>
    </AppLayout>

</template>

<script setup>
    // Imports
    import AppLayout from "@/Layouts/AppLayout.vue";
    import Container from "@/Components/Container.vue";
    import InputError from "@/Components/InputError.vue";
    import InputLabel from "@/Components/InputLabel.vue";
    import PrimaryButton from "@/Components/PrimaryButton.vue";
    import TextInput from "@/Components/TextInput.vue";
    import { useForm } from "@inertiajs/vue3";


    const form = useForm ({
        title: '',
        body: '',
    });

    const submit = () => {
        form.post(route('posts.store'), {
            onSuccess: () => form.reset()
        });
    };
</script>