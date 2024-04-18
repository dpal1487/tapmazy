<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import PrimaryButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";
import useVuelidate from "@vuelidate/core";
import { required, url, integer } from "@vuelidate/validators";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

export default defineComponent({
    props: ['employee', 'departments','roles'],
    emits: ["submitted"],
    setup() {
        return { v$: useVuelidate() };
    },
    validations() {
        return {
            form: {
                first_name: {
                    required,
                },
                last_name: {
                    required,
                },
                email: {
                    required,
                },
                password: {

                },
                confirm_password: {

                },
                date_of_joining: {
                    required,
                },
                emergency_number: {
                    required, integer,
                },
                pan_number: {
                    required,
                },
                father_name: {
                    required,
                },
                salary: {
                    required,
                },
                department: {
                    required,
                },
                role: {
                    required,
                }
            },
        };
    },
    data() {
        return {
            processing: false,
            isUploading: false,
            form: this.$inertia.form({
                id: this.employee?.data?.id || '',
                image: this.employee?.data?.image?.medium_path || '',
                image_id: this.employee?.data?.image_id || '',
                first_name: this.employee?.data?.first_name || '',
                last_name: this.employee?.data?.last_name || '',
                email: this.user?.data?.email || '',
                password: this.user?.data?.password || '',
                confirm_password: '',
                date_of_joining: this.employee?.data?.date_of_joining || '',
                emergency_number: this.employee?.data?.emergency_number || '',
                pan_number: this.employee?.data?.pan_number || '',
                father_name: this.employee?.data?.father_name || '',
                salary: this.employee?.data?.salary || '',
                department: this.employee?.data?.department?.id || '',
                role: this.employee?.data?.role?.id || '',
            }),
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        Multiselect,
        PrimaryButton,
        JetInput,
        JetLabel,
        InputError,
        VueDatePicker,
        JetValidationErrors
    },
    methods: {
        submit(action) {
            this.form.action = action;
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.$emit('submitted', this.form)
            }
        },
        onFileChange(e) {
            const file = e.target.files[0];
            this.$data.form.image = file;
            this.selectedFilename = file?.name;
            this.url = URL.createObjectURL(file);
            const formdata = new FormData();
            formdata.append("image", file)
            this.isUploading = true;
            axios.post("/employee/image-upload", formdata, {
                headers: {
                    "Content-Type": "multipart/form-data",
                }
            }).then((response) => {
                if (response.data.success) {
                    // toast.success(response.data.message);
                    this.form.image_id = response.data.data.id;
                } else {
                    toast.error(response.data.message);
                }
            }).finally(() => {
                this.isUploading = false;
            })
        },
    },
});
</script>
<template>
    <JetValidationErrors />
    <form @submit.prevent="submit()">
        <div class="row">
            <div class="fv-row col-6 mb-3">
                <jet-label for="first_name" value="First Name" />
                <jet-input id="first_name" type="text" v-model="v$.form.first_name.$model" :class="v$.form.first_name.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="First name" />
                <div v-for="(error, index) of v$.form.first_name.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-6 mb-3">
                <jet-label for="last_name" value="Last Name" />
                <jet-input id="last_name" type="text" v-model="v$.form.last_name.$model" :class="v$.form.last_name.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Last Name" />
                <div v-for="(error, index) of v$.form.last_name.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-6 mb-3">
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" v-model="v$.form.email.$model" :class="v$.form.email.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Email" />
                <div v-for="(error, index) of v$.form.email.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-6 mb-3">
                <jet-label for="father_name" value="Father Name" />
                <jet-input id="father_name" type="text" v-model="v$.form.father_name.$model" :class="v$.form.father_name.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Father Name" />
                <div v-for="(error, index) of v$.form.father_name.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-6 mb-3">
                <jet-label for="department" value="Department" />
                <Multiselect :canClear="false" :options="departments" label="name" valueProp="id"
                    class="form-control form-control-lg form-control-solid" placeholder="Select One"
                    v-model="form.department" track-by="name" />
                <div v-for="(error, index) of v$.form.department.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-6 mb-3">
                <jet-label for="role" value="Roles" />
                <Multiselect :canClear="false" :options="roles" label="name" valueProp="id"
                    class="form-control form-control-lg form-control-solid" placeholder="Select One"
                    v-model="form.role" track-by="name" />
                <div v-for="(error, index) of v$.form.role.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-6 mb-3">
                <jet-label for="password" value="Password" />
                <jet-input id="password" type="password" v-model="v$.form.password.$model" :class="v$.form.password.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Password" />
                <div v-for="(error, index) of v$.form.password.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>

            <div class="fv-row col-6 mb-3">
                <jet-label for="confirm_password" value="Confirm Password" />
                <jet-input id="confirm_password" type="password" v-model="v$.form.confirm_password.$model" :class="v$.form.confirm_password.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Confirm Password" />
                <div v-for="(error, index) of v$.form.confirm_password.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-6 mb-3">
                <jet-label for="date_of_joining" value="Date Of Joining" />
                <VueDatePicker v-model="v$.form.date_of_joining.$model" :enable-time-picker="false" auto-apply
                    input-class-name="form-control form-control-lg form-control-solid fw-normal" :class="v$.form.date_of_joining.$errors.length > 0
                        ? 'is-invalid'
                        : ''
                        " placeholder="Date Of Joining"></VueDatePicker>
                <div v-for="(error, index) of v$.form.date_of_joining.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-6 mb-3">
                <jet-label for="emergency_number" value="Emergency Number" />
                <jet-input id="emergency_number" type="text" v-model="v$.form.emergency_number.$model" :class="v$.form.emergency_number.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Emergency Number" />
                <div v-for="(error, index) of v$.form.emergency_number.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-6 mb-3">
                <jet-label for="pan_number" value="Pan Number" />
                <jet-input id="pan_number" type="text" v-model="v$.form.pan_number.$model" :class="v$.form.pan_number.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Pan Number" />
                <div v-for="(error, index) of v$.form.pan_number.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-6 mb-3">
                <jet-label for="salary" value="Salary" />
                <jet-input id="salary" type="text" v-model="v$.form.salary.$model" :class="v$.form.salary.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Salary" />
                <div v-for="(error, index) of v$.form.salary.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
        </div>
        <slot name="action"></slot>
    </form>
</template>
