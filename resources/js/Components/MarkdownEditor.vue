<template>
    <div v-if="editor" class="bg-white rounded-md border-0 shadow-sm ring-1 ring-inset ring-gray-300 focus:rinng-2 focus:ring-indigo-600">
        <menu class="flex divide-x border-b border-gray-200 bg-gray-50 rounded-t-md">
            <li>
                <button @click="() => editor.chain().focus().toggleBold().run()" 
                    type="button" 
                    class="px-3 py-2 rounded-tl-md" 
                    :class="[ editor?.isActive('bold') ? 'bg-gray-200': 'hover:bg-gray-200',]"
                    title="Bold">
                        <i class="ri-bold"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleItalic().run()" 
                    type="button" 
                    class="px-3 py-2" 
                    :class="[ editor?.isActive('italic') ? 'bg-gray-200': 'hover:bg-gray-200',]"
                    title="Italic">
                        <i class="ri-italic"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleStrike().run()" 
                    type="button" 
                    class="px-3 py-2" 
                    :class="[ editor?.isActive('strike') ? 'bg-gray-200': 'hover:bg-gray-200',]"
                    title="Strikethrough">
                        <i class="ri-strikethrough"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleBlockquote().run()" 
                    type="button" 
                    class="px-3 py-2" 
                    :class="[ editor?.isActive('blockquote') ? 'bg-gray-200': 'hover:bg-gray-200',]"
                    title="Blockquote">
                        <i class="ri-double-quotes-l"></i>
                </button>
            </li>
        </menu>
        <EditorContent :editor="editor"/>
    </div>
</template>

<script setup>
    import StarterKit from '@tiptap/starter-kit';
    import { EditorContent, useEditor } from '@tiptap/vue-3';
    import { Markdown } from 'tiptap-markdown';
    import { watch } from 'vue';
    import 'remixicon/fonts/remixicon.css'


    const props = defineProps({
        modelValue: ','
    });

    const emit = defineEmits(['update:modelValue']);

    const editor = useEditor({
        extensions: [
            StarterKit,
            Markdown,
        ],
        editorProps: {
            attributes: {
                class: 'min-h-[512px] prose prose-sm max-w-none py-1.5 px-3',
            },
        },
        onUpdate: () => emit('update:modelValue', editor.value?.storage.markdown.getMarkdown()),
    });

    watch(() => props.modelValue, (value) => {
        if (value !== editor.value?.storage.markdown.getMarkdown()) {
            editor.value?.commands.setContent(value || '');
        }
    }, { immediate: true });


</script>