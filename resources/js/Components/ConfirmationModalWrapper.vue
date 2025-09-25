<template>
    <ConfirmationModal :show="modelValue" @close="emitClose">
        <template #title>
            {{ title || "Confirm" }}
        </template>

        <template #content>
            {{ message || "Are you sure?" }}
        </template>

        <template #footer>
            <SecondaryButton @click="emitClose" :disabled="processing">
                {{ cancelText }}
            </SecondaryButton>
            <PrimaryButton class="ml-3"  @click="emitConfirm" :disabled="processing">
                {{ confirmText }}
            </PrimaryButton>
        </template>
    </ConfirmationModal>
</template>

<script setup>
import ConfirmationModal from "./ConfirmationModal.vue";
import PrimaryButton from "./PrimaryButton.vue";
import SecondaryButton from "./SecondaryButton.vue";

const props = defineProps({
    modelValue: { type: Boolean, default: false }, // v-model support
    title: { type: String, default: "Delete" },
    message: {
        type: String,
        default: "Are you sure? This action cannot be undone.",
    },
    confirmText: { type: String, default: "Delete" },
    cancelText: { type: String, default: "Cancel" },
    processing: { type: Boolean, default: false },
});

const emit = defineEmits(["update:modelValue", "confirm", "cancel"]);

const emitClose = () => {
    emit("update:modelValue", false);
    emit("cancel");
};

const emitConfirm = () => {
    emit("confirm");
};
</script>

<!-- <template>
    <ConfirmationModal :show="true">
        <template #title>
            Delete Comment
        </template>

        <template #content>
            Are you sure you want to delete this comment? This action cannnot be undone.
        </template>

        <template #footer>
            <SecondaryButton>Cancel</SecondaryButton>
            <PrimaryButton class="ml-3">Delete</PrimaryButton>
        </template>
    </ConfirmationModal>
</template>

<script setup>
import ConfirmationModal from './ConfirmationModal.vue';
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';



</script> -->
