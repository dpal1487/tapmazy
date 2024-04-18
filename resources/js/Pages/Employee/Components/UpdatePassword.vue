<script>
import { defineComponent } from 'vue';
import useVuelidate from '@vuelidate/core';
import { required, } from '@vuelidate/validators';
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";

import { toast } from 'vue3-toastify';

export default defineComponent({
    props: ["password", "employee"],
    setup() {
        return { v$: useVuelidate() };
    },
    validations() {
        return {
            form: {
                new_password: {
                    required,
                },
                confirm_password: {
                    required,
                }
            }
        }
    },
    data() {
        return {
            isEdit: false,
            form: {
                id: this.employee?.id,
                new_password: '',
                confirm_password: '',
            }
        }
    },
    components: {
        JetInput,
        JetLabel,
        InputError
    },
    methods: {
        showChangePassword() {
            this.isEdit = true;
        },
        hideChangePassword() {
            this.isEdit = false;
        },
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                axios.post(route('employee.changePassword', this.form.id), this.form).then((response) => {
                    if (response.data.success) {
                        toast.success(response.data.message);
                        this.isEdit = false;
                        return;
                    }
                    toast.error(response.data.message);
                });
                return;
            }
        },
    }
})

</script>
<template>
    <div v-if="isEdit" class="flex-row-fluid">
        <!--begin::Form-->
        <form @submit.prevent="submit()">
            <div class="row">
                <div class="col-lg-4">
                    <div class="fv-row mb-0">
                        <jet-label for="new_password" value="New Password" />
                        <jet-input id="new_password" type="password" v-model="v$.form.new_password.$model" :class="v$.form.new_password.$errors.length > 0
                            ? 'is-invalid'
                            : ''
                            " placeholder="New Password" />
                             <div v-for="(error, index) of v$.form.new_password.$errors" :key="index">
                            <input-error :message="error.$message" />
                        </div>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="fv-row mb-0">
                        <jet-label for="confirm_password" value="Confirm Password" />
                        <jet-input id="confirm_password" type="password" v-model="v$.form.confirm_password.$model" :class="v$.form.confirm_password.$errors.length > 0
                            ? 'is-invalid'
                            : ''
                            " placeholder="Confirm Password" />
                             <div v-for="(error, index) of v$.form.confirm_password.$errors" :key="index">
                            <input-error :message="error.$message" />
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-text mb-5">Password must be at least 8 character and contain symbols
            </div>
            <div class="d-flex gap-5">
                <button type="submit" class="btn btn-primary" :class="{ 'text-white-50': form.processing }">
                    <div v-show="form.processing" class="spinner-border spinner-border-sm">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Change Password
                </button>
                <button type="button" @click="hideChangePassword"
                    class="btn btn-outline-secondary d-flex align-items-center justify-content-center">Discard</button>
                <span class="indicator-progress">
                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </div>
        </form>
        <!--end::Form-->
    </div>

    <div v-else class="d-flex flex-wrap align-items-center mb-10">
        <!--begin::Label-->
        <div>
            <div class="fs-6 fw-bold mb-1">Password</div>
            <div class="fw-semibold text-gray-600">************</div>
        </div>
        <!--end::Label-->
        <!--begin::Edit-->

        <!--end::Edit-->
        <!--begin::Action-->
        <div class="ms-auto">
            <button class="btn btn-light btn-active-light-primary" @click="showChangePassword">Reset Password</button>
        </div>
        <!--end::Action-->
    </div>
</template>
