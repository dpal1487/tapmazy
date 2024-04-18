<script>
import { defineComponent } from 'vue';
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import useVuelidate from '@vuelidate/core';
import { required, email, sameAs } from "@vuelidate/validators";
import { toast } from 'vue3-toastify';
import axios from "axios";
import InputError from "@/jetstream/InputError.vue";
export default defineComponent({
    props: ["employee"],
    setup() {
        return { v$: useVuelidate() };
    },
    validations() {
        return {
            form: {
                email: {
                    required,
                    email
                },
            }
        }
    },
    data() {
        return {
            isEdit: false,
            form: {
                id: this.employee?.id,
                email: this.employee?.email,
            },
        }
    },
    components: {
        JetInput,
        JetLabel,
        InputError
    },
    methods: {
        showEditEmail() {
            this.isEdit = true;
        },
        hideEditEmail() {
            this.isEdit = false;
        },
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                if (this.form.email != this.employee.email) {
                    axios.post(route('employee.updateEmail', this.form.id), this.form).then((response) => {
                        if (response.data.success) {
                            toast.success(response.data.message);
                            this.isEdit = false;
                            return;
                        }
                        toast.error(response.data.message);
                    });
                    return;
                }
                toast.error("Use diffrent email address.");
            }
        },
    }
})
</script>

<template>
    <div v-if="isEdit" class="flex-row-fluid">
        <form @submit.prevent="submit()" class="">
            <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="fv-row mb-0">
                        <jet-label for="email" value="Enter New
                            Email Address" />
                        <jet-input id="email" type="email" v-model="v$.form.email.$model" :class="v$.form.email.$errors.length > 0
                            ? 'is-invalid'
                            : ''
                            " placeholder="Email Id" />
                        <div v-for="(error, index) of v$.form.email.$errors" :key="index">
                            <input-error :message="error.$message" />
                        </div>

                    </div>
                </div>
            </div>
            <div class="d-flex gap-5">
                <button type="submit" class="btn btn-primary" :class="{ 'text-white-50': form.processing }">
                    <div v-show="form.processing" class="spinner-border spinner-border-sm">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Change Email
                </button>
                <button type="button" @click="hideEditEmail"
                    class="btn btn-outline-secondary d-flex align-items-center justify-content-center">Discard</button>
            </div>
        </form>
        <!--end::Form-->
    </div>
    <div v-else class="d-flex flex-wrap align-items-center">
        <div>
            <div class="fs-6 fw-bold mb-1">Email Address</div>
            <div class="fw-semibold text-gray-600">{{ employee?.email }}</div>
        </div>
        <!--end::Label-->
        <!--begin::Action-->
        <div class="ms-auto">
            <button type="button" @click="showEditEmail" class="btn btn-light btn-active-light-primary">Change
                Email</button>
        </div>
        <!--end::Action-->
    </div>
</template>
