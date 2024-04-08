<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="mt-6 space-y-6"
        >
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <div class="container">
                        <div class="avatar-upload">
                            <div class="avatar-edit">
                                <input
                                    type="file"
                                    id="imageUpload"
                                    accept=".png, .jpg, .jpeg"
                                    @change="readURL($event)"
                                />
                                <label for="imageUpload"></label>
                            </div>
                            <div class="avatar-preview">
                                <div
                                    id="imagePreview"
                                    style="
                                        background-image: url(http://i.pravatar.cc/500?img=7);
                                    "
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <!-- Name -->
                    <div class="m-2">
                        <InputLabel for="name" value="Name" :required="true" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <!-- Email -->
                    <div class="m-2">
                        <InputLabel
                            for="email"
                            value="Email"
                            :required="true"
                        />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Phone No -->
                    <div class="m-2">
                        <InputLabel for="phoneno" value="Phone No" />

                        <MobileNumber
                            id="phoneno"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.phoneno"
                            required
                            autocomplete="phoneno"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.phoneno"
                        />
                    </div>
                </div>

                <div>
                    <!-- Address -->
                    <div class="m-2">
                        <InputLabel for="address" value="Address" />

                        <TextArea
                            id="address"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.address"
                            required
                            autocomplete="address"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.address"
                        />
                    </div>
                    <div class="m-2">
                        <InputLabel for="city" value="City" />

                        <TextInput
                            id="city"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.city"
                            required
                            autocomplete="city"
                        />

                        <InputError class="mt-2" :message="form.errors.city" />
                    </div>
                </div>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<script>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import MobileNumber from "@/Components/MobileNumber.vue";
import { Link, usePage } from "@inertiajs/vue3";

export default {

    components: {
        InputError,
        InputLabel,
        PrimaryButton,
        TextInput,
        TextArea,
        MobileNumber,
        Link
    },

    props: {
        mustVerifyEmail: {
            type: Boolean,
        },
        status: {
            type: String,
        },
    },
    data() {
        return {
            form: this.$inertia.form({
                name: this.$page.props.auth.user.name,
                email: this.$page.props.auth.user.email,
                photo: this.$page.props.auth.user?.photo ?? [],
                phone: this.$page.props.auth.user?.phoneno ?? '',
                address: this.$page.props.auth.user?.address ?? '',
                city: this.$page.props.auth.user?.city ?? '',
            })
        }
    },

    methods: {
        readURL(input) {
            let file = input.target.files
            this.form.photo = file;
            if (file && file[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let imagePreview = document.getElementById('imagePreview');
                    imagePreview.style.backgroundImage = 'url(' + e.target.result + ')';

                    imagePreview.style.display = 'none';

                    setTimeout(function() {
                        imagePreview.style.display = 'block';
                        imagePreview.style.opacity = 0;
                        let fadeInInterval = setInterval(function() {
                            imagePreview.style.opacity = parseFloat(imagePreview.style.opacity) + 0.05;
                            if (parseFloat(imagePreview.style.opacity) >= 1) {
                                clearInterval(fadeInInterval);
                            }
                        }, 13);
                    }, 0);
                }
                reader.readAsDataURL(file[0]);
            }
        }
    },
}
</script>

<style>
body {
    background: whitesmoke;
    font-family: "Open Sans", sans-serif;
}

.container {
    max-width: 960px;
    margin: 30px auto;
}

.avatar-upload {
    position: relative;
    max-width: 205px;
    margin: 50px auto;
    .avatar-edit {
        position: absolute;
        right: 12px;
        z-index: 1;
        top: 10px;
        input {
            display: none;
            + label {
                display: inline-block;
                width: 34px;
                height: 34px;
                margin-bottom: 0;
                border-radius: 100%;
                background: #ffffff;
                border: 1px solid transparent;
                box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
                cursor: pointer;
                font-weight: normal;
                transition: all 0.2s ease-in-out;
                &:hover {
                    background: #f1f1f1;
                    border-color: #d6d6d6;
                }
                &:after {
                    content: "\f040";
                    font-family: "FontAwesome";
                    color: #757575;
                    position: absolute;
                    top: 10px;
                    left: 0;
                    right: 0;
                    text-align: center;
                    margin: auto;
                }
            }
        }
    }
    .avatar-preview {
        width: 192px;
        height: 192px;
        position: relative;
        border-radius: 100%;
        border: 6px solid #f8f8f8;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        > div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
    }
}
</style>
