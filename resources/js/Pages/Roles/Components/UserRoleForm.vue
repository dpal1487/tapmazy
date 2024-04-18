<script>
import { defineComponent } from 'vue';
import { required } from '@vuelidate/validators';
import JetInput from "@/Jetstream/Input.vue";
import InputError from "@/jetstream/InputError.vue";
import Modal from '@/Components/Modal.vue';
import { toast } from 'vue3-toastify';
import axios from 'axios';
import useVuelidate from '@vuelidate/core';

export default defineComponent({

    props: ['isEdit', 'show', 'permissions', 'role', 'menus'],
    emits: ['hidemodal'],

    setup() {
        return {
            v$: useVuelidate()
        }
    },
    validations() {
        return {
            form: {
                name: {
                    required,
                },
            }
        }
    },
    data() {
        return {
            read: '',
            showModal: false,
            processing: false,
            admin_access: false,
            form: this.$inertia.form({
                role: '',
                permissions: []
            }),
        }
    },
    components: {
        Modal,
        JetInput,
        InputError,
    },
    methods: {
        submit() {
            this.v$.$touch();
            const resultArray = [];
            this.form.permissions.forEach(element => {
                for (let key in element) {
                    if (key !== "page_name") {
                        if (element[key]) {
                            resultArray.push(`${element.page_name}.${key}`)
                        }
                    }
                }
            });
            if (!this.v$.form.$invalid) {
                this.processing = true;
                axios
                    .post('/role', { ...this.form, permissions: resultArray })
                    .then((response) => {
                        if (response?.data?.success) {
                            toast.success(response?.data?.message)
                            this.processing = false;
                            this.$emit('hidemodal')
                        }
                        else {
                            toast.error(response?.data?.message)
                        }
                    });
            }
        },
        handleCheckChange(event, index, field) {
            this.form.permissions[index][field] = event.target.checked;
            if (field === "read" && !event.target.checked) {
                this.form.permissions[index].write = false;
                this.form.permissions[index].delete = false;
            }
        }
    },
    mounted() {
        this.form.permissions = this.menus.map(m => ({
            page_name: m.title,
            read: false,
            write: false,
            delete: false,
        }))
    },
    watch: {
        admin_access: {
            deep: true,
            async handler() {
                if (this.admin_access) {
                    this.form.permissions = this.form.permissions.map(p => ({ ...p, read: true, write: true, delete: true }))
                } else {
                    this.form.permissions = this.form.permissions.map(p => ({ ...p, read: false, write: false, delete: false }))
                }
            }
        }
    },
    // watch: {
    //     form: {
    //         deep: true,
    //         async handler() {
    //             const obj = this.form.permissions.find(p => !p.read && !p.write && !p.delete);
    //             console.log("see this", obj)
    //         }
    //     }
    // }
})
</script>

<template>
    <Modal :show="show" :title="isEdit ? 'Edit Role' : 'Add Role'" @onhide="$emit('hidemodal', false)">
        <div class="modal-body scroll-y mx-lg-5">
            <form @submit.prevent="submit()" class="form">
                <!--begin::Scroll-->
                <div class="d-flex flex-column scroll-y" data-kt-scroll="true"
                    data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                    data-kt-scroll-offset="300px">
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="fs-5 fw-bold form-label mb-2">
                            <span class="required">Role name</span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <jet-input id="name" type="text" v-model="form.name" :class="v$.form.name.$errors.length > 0
                            ? 'is-invalid'
                            : ''
                            " placeholder="Enter a role name" />
                        <div v-for="(error, index) of v$.form.name.$errors" :key="index">
                            <input-error :message="error.$message" />
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Permissions-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="fs-5 fw-bold form-label mb-2">Role Permissions</label>
                        <!--end::Label-->
                        <!--begin::Table wrapper-->
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <!--begin::Table body-->
                                <tbody class="text-gray-600 fw-semibold">
                                    <!--begin::Table row-->
                                    <tr>
                                        <td class="text-gray-800">Administrator Access
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                title="Allows a full access to the system"></i>
                                        </td>
                                        <td>
                                            <!--begin::Checkbox-->
                                            <label class="form-check form-check-custom form-check-solid me-9"
                                                style="width: fit-content;">
                                                <input class="form-check-input" type="checkbox" v-model="admin_access"
                                                    id="roles_select_all" />
                                                <span class="form-check-label" for="roles_select_all">Select
                                                    all</span>
                                            </label>
                                            <!--end::Checkbox-->
                                        </td>
                                    </tr>
                                    <!--end::Table row-->
                                    <!--begin::Table row-->
                                    <tr v-for="(menu, index) in menus" :key="index">
                                        <!--begin::Label-->
                                        <td class="text-red-800 text-uppercase">{{ menu.title }} </td>
                                        <!--end::Label-->
                                        <!--begin::Options-->
                                        <td>
                                            <!--begin::Wrapper-->
                                            <div class="d-flex">
                                                <!--begin::Checkbox-->
                                                <label
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-8">
                                                    <input id="read" class="form-check-input" type="checkbox"
                                                        :v-model="form.permissions[index]?.read"
                                                        :checked="form.permissions[index]?.read || admin_access"
                                                        @change="(event) => handleCheckChange(event, index, 'read')" />
                                                    <span class="form-check-label">Read</span>
                                                </label>
                                                <!--end::Checkbox-->
                                                <!--begin::Checkbox-->
                                                <label
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-8">
                                                    <input class="form-check-input" type="checkbox"
                                                        :v-model="form.permissions[index]?.write"
                                                        :disabled="!form.permissions[index]?.read"
                                                        :checked="form.permissions[index]?.write"
                                                        @change="(event) => handleCheckChange(event, index, 'write')" />
                                                    <span class="form-check-label">Write</span>
                                                </label>
                                                <!--end::Checkbox-->
                                                <!--begin::Checkbox-->
                                                <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        :v-model="form.permissions[index]?.delete"
                                                        :disabled="!form.permissions[index]?.read"
                                                        :checked="form.permissions[index]?.delete"
                                                        @change="(event) => handleCheckChange(event, index, 'delete')" />
                                                    <span class="form-check-label">Delete</span>
                                                </label>
                                                <!--end::Checkbox-->
                                            </div>
                                            <!--end::Wrapper-->
                                        </td>
                                        <!--end::Options-->
                                    </tr>
                                    <!--end::Table row-->
                                </tbody>
                                <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Table wrapper-->
                    </div>
                    <!--end::menus-->
                </div>
                <!--end::Scroll-->
                <!--begin::Actions-->
                <div class="text-center pt-15">
                    <button type="reset" class="btn btn-light me-3" @onhide="$emit('hidemodal', false)">Discard</button>
                    <button type="submit" class="btn btn-primary" :data-kt-indicator="processing ? 'on' : 'off'">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Modal body-->
    </Modal>
</template>