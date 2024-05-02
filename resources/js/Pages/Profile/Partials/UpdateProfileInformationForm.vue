<template>
    <section>
        <loading v-model:active="isLoading" :loader="'dots'" />
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="submitForm" class="mt-6 space-y-6">
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
                                <label for="imageUpload"><i></i></label>
                            </div>
                            <div class="avatar-preview">
                                <div id="imagePreview"></div>
                            </div>
                            <p v-if="uploadnewPic" class="text-xs">
                                save your pic to clicking save button
                            </p>
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
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.phoneno"
                        />
                    </div>
                    <div class="m-2">
                        <InputLabel for="state" value="State" />

                        <TextInput
                            id="state"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.state"
                        />

                        <InputError class="mt-2" :message="form.errors.city" />
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
                        />

                        <InputError class="mt-2" :message="form.errors.city" />
                    </div>
                    <div class="m-2">
                        <InputLabel for="country" value="Country" />

                        <SingleSelect
                            :key="form.country"
                            v-model="form.country"
                            placeholder="Select Country"
                            class="w-full mt-2"
                            :options="countryData"
                            @getValue="getCountryDataFunc($event)"
                        />

                        <InputError class="mt-2" :message="form.errors.city" />
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
            </div>
        </form>
    </section>
</template>

<script>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SingleSelect from "@/Components/SingleSelect.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import MobileNumber from "@/Components/MobileNumber.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { countries, getCountryData } from "countries-list";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";

export default {
    components: {
        InputError,
        InputLabel,
        PrimaryButton,
        TextInput,
        TextArea,
        MobileNumber,
        Link,
        SingleSelect,
        Loading,
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
                phoneno: this.$page.props.auth.userInfo?.phoneno ?? "",
                address: this.$page.props.auth.userInfo?.address ?? "",
                city: this.$page.props.auth.userInfo?.city ?? "",
                state: this.$page.props.auth.userInfo?.state ?? "",
                country: this.$page.props.auth.userInfo?.country ?? "",
            }),
            uploadnewPic: false,
            countryData: [],
            isLoading: false,
        };
    },

    mounted() {
        let imagePreview = document.getElementById("imagePreview");
        imagePreview.style.backgroundImage = "url(" + this.form.photo + ")";
        this.countryData = Object.keys(countries).map((data) => {
            let obj = {};
            obj.label = countries[data].name;
            obj.value = data;
            return obj;
        });
    },

    methods: {
        readURL(input) {
            let file = input.target.files;
            this.form.photo = file[0];
            console.log(this.form.photo);
            if (file && file[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let imagePreview = document.getElementById("imagePreview");
                    imagePreview.style.backgroundImage =
                        "url(" + e.target.result + ")";

                    imagePreview.style.display = "none";

                    setTimeout(function () {
                        imagePreview.style.display = "block";
                        imagePreview.style.opacity = 0;
                        let fadeInInterval = setInterval(function () {
                            imagePreview.style.opacity =
                                parseFloat(imagePreview.style.opacity) + 0.05;
                            if (parseFloat(imagePreview.style.opacity) >= 1) {
                                clearInterval(fadeInInterval);
                            }
                        }, 13);
                    }, 0);
                };
                reader.readAsDataURL(file[0]);
                this.uploadnewPic = true;
            }
        },

        getCountryDataFunc(con) {
            let data = getCountryData(con);
            console.log(data);
        },

        submitForm() {
            this.isLoading = true;
            const formData = new FormData();
            formData.append("name", this.form.name);
            formData.append("email", this.form.email);
            formData.append("photo", this.form.photo);
            formData.append("address", this.form.address);
            formData.append("city", this.form.city);
            formData.append("country", this.form.country);
            formData.append("state", this.form.state);
            formData.append("phoneno", this.form.phoneno);
            axios.post(this.route("profile.update"), formData).then((res) => {
                console.log("rerer - ", res);
                this.isLoading = false;
                toast(res.data.message, {
                    theme: "dark",
                    type: res.data.status == 200 ? "success" : "error",
                    dangerouslyHTMLString: true,
                });
            });
        },
    },
};
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
