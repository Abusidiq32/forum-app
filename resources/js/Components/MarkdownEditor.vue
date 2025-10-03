<template>
    <div v-if="editor" class="bg-white rounded-md border-0 shadow-sm ring-1 ring-inset ring-gray-300 focus:rinng-2 focus:ring-indigo-600">
        <menu class="flex divide-x border-b border-gray-200 bg-gray-50 rounded-t-md">
            <li>
                <button @click="() => editor.chain().focus().toggleHeading({ level: 2}).run()" 
                    type="button" 
                    class="px-3 py-2 rounded-tl-md" 
                    :class="[ editor?.isActive('heading', { level: 2}) ? 'bg-gray-200': 'hover:bg-gray-200',]"
                    title="Heading 1">
                        <i class="ri-h-1"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleHeading({ level: 3}).run()" 
                    type="button" 
                    class="px-3 py-2 rounded-tl-md" 
                    :class="[ editor?.isActive('heading', { level: 3}) ? 'bg-gray-200': 'hover:bg-gray-200',]"
                    title="Heading 2">
                        <i class="ri-h-2"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleHeading({ level: 4}).run()" 
                    type="button" 
                    class="px-3 py-2 rounded-tl-md" 
                    :class="[ editor?.isActive('heading', { level: 4}) ? 'bg-gray-200': 'hover:bg-gray-200',]"
                    title="Heading 3">
                        <i class="ri-h-3"></i>
                </button>
            </li>
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
            <li>
                <button @click="() => editor.chain().focus().toggleBulletList().run()" 
                    type="button" 
                    class="px-3 py-2" 
                    :class="[ editor?.isActive('bulletList') ? 'bg-gray-200': 'hover:bg-gray-200',]"
                    title="Bullet List">
                        <i class="ri-list-unordered"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleOrderedList().run()" 
                    type="button" 
                    class="px-3 py-2" 
                    :class="[ editor?.isActive('orderedList') ? 'bg-gray-200': 'hover:bg-gray-200',]"
                    title="Ordered List">
                        <i class="ri-list-ordered"></i>
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
            StarterKit.configure({
                heading: {
                    levels: [2, 3, 4],
                },
            }),
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