<template>
    <div class="relative">
        <div class="absolute inset-y-0 start-0 top-0 flex items-center ps-3.5 pointer-events-none">
            <svg class="w-4 h-4 text-gray-700 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 18">
                <path d="M18 13.446a3.02 3.02 0 0 0-.946-1.985l-1.4-1.4a3.054 3.054 0 0 0-4.218 0l-.7.7a.983.983 0 0 1-1.39 0l-2.1-2.1a.983.983 0 0 1 0-1.389l.7-.7a2.98 2.98 0 0 0 0-4.217l-1.4-1.4a2.824 2.824 0 0 0-4.218 0c-3.619 3.619-3 8.229 1.752 12.979C6.785 16.639 9.45 18 11.912 18a7.175 7.175 0 0 0 5.139-2.325A2.9 2.9 0 0 0 18 13.446Z"/>
            </svg>
        </div>
        <input
            aria-describedby="helper-text-explanation"
            ref="input"
            v-maska
            :class="[
                'outline-none focus:ring-0 focus:ring-opacity-0  border border-gray-300 focus:border-blue-600 rounded-md shadow-sm inline-block w-full text-fs13 mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
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
    </div>
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
