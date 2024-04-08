<template>
    <input
        ref="input"
        v-maska
        :class="[
            'outline-none focus:ring-0 focus:ring-opacity-0  border border-gray-300 focus:border-blue-600 rounded-md shadow-sm inline-block w-full text-fs13 mt-1',
            smallsize ? 'py-1' : '',
        ]"
        :value="modelValue"
        :disabled="disabled"
        :type="type"
        :maxlength="maxlength"
        data-maska="###-###-####"
        @input="$emit('update:modelValue', $event.target.value)"
        @keypress="allowNumbersOnly($event)"
    />
</template>
<script>
import { vMaska } from "maska";
import { onMounted, ref } from "vue";
export default {
    emits: ["update:modelValue"],
    directives: { maska: vMaska },
    props: {
        modelValue: {
            type: [String, Number],
            default: "",
        },
        type: {
            type: [String, Number],
            default: "text",
        },
        smallsize: {
            type: Boolean,
            default: false,
        },
        maxlength: {
            type: [String, Number],
            default: "",
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            notevalue: "",
        };
    },
    mounted() {
        this.notevalue = this.values;
    },
    methods: {
        async getData(data) {
            await this.$emit("returnData", data);
        },
        allowNumbersOnly($event) {
            if (this.type == "number") {
                console.log(this.type);
                console.log($event.keyCode);
                let keyCode = $event.keyCode ? $event.keyCode : $event.which;
                if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
                    $event.preventDefault();
                }
            }
        },
    },
    setup() {
        const input = ref(null);
        onMounted(async () => {
            if (input.value.hasAttribute("autofocus")) {
                input.value.focus();
            }
        });
        return {
            input,
        };
    },
};
</script>
