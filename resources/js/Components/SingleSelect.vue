<template>
    <div class="singleselect">
        <Multiselect
            :key="key"
            v-model="notevalue"
            :class="[
                standard ? 'standardSelect' : '',
                smallsize ? 'py-1' : '',
                classes,
            ]"
            mode="single"
            :can-clear="clearbtn"
            as-single
            :options="options"
            :searchable="searchable"
            :placeholder="placeholder"
            :disabled="disabled"
            :can-deselect="deselect"
            :select="$emit('update:modelValue', notevalue)"
            @change="$emit('getValue', $event)"
        />
    </div>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<script>
import Multiselect from "@vueform/multiselect";
import { ref } from "vue";
export default {
    components: {
        Multiselect,
    },
    emits: ["update:modelValue", "getValue"],
    props: {
        options: {
            type: [Object, Array],
            default: null,
        },
        searchable: {
            type: [Boolean],
            default: true,
        },
        model: {
            type: Number,
            default: null,
        },
        modelValue: {
            type: [Number, String],
            default: null,
        },
        placeholder: {
            type: [String],
            default: null,
        },
        disabled: {
            type: [Boolean],
            default: false,
        },
        smallsize: {
            type: Boolean,
            default: false,
        },
        clearbtn: {
            type: [Boolean],
            default: true,
        },
        key: {
            type: [Number, String],
            default: 1,
        },
        deselect: {
            type: Boolean,
            default: true,
        },
        standard: {
            type: Boolean,
            default: false,
        },
        classes: {
            type: [String],
            default: "",
        },
    },
    setup(props) {
        const notevalue = ref("");
        notevalue.value = props.modelValue;
        return {
            notevalue,
        };
    },
    methods: {
        setValue(value) {
            this.notevalue = value;
            this.$emit("update:modelValue", value);
        },
    },
};
</script>
