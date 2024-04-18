<script>
import { defineComponent } from 'vue';
import { required } from '@vuelidate/validators';
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import InputError from "@/jetstream/InputError.vue";
import Modal from '@/Components/Modal.vue';
import { toast } from 'vue3-toastify';
import axios from 'axios';
import useVuelidate from '@vuelidate/core';
import { Inertia } from '@inertiajs/inertia';

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
                role: {
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
        JetLabel,
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
                    .post(this.isEdit ? route('role.update', this.role) : route('role.store'), { ...this.form, permissions: resultArray })

                    .then((response) => {
                        if (response?.data?.success) {
                            toast.success(response?.data?.message)
                            this.processing = false;
                            Inertia.get('role')
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
    async mounted() {
        if (this.role !== '') {
            this.isLoading = true;
            const response = await axios.get(`/role/${this.role}/edit`);
            const fetched_permissions = response?.data?.permissions;
            const finalMutated_permissions = [];

            this.menus?.forEach(m => {
                const sets = fetched_permissions.filter(p => p.name?.startsWith(m.title));
                const form_permission = { page_name: m?.title };
                sets?.forEach(s => {
                    let [_, access] = s.name?.split(".");
                    form_permission[access] = true;
                })
                finalMutated_permissions.push(form_permission)
            })

            this.form.role = response?.data?.role?.name;
            this.form.permissions = finalMutated_permissions;
            this.form.id = this.id;
            this.isLoading = false;
        }
        if (!this.isEdit) {
            this.form.permissions = this.menus.map(m => ({
                page_name: m.title,
                read: false,
                write: false,
                delete: false,
            }))
        }
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
        },
    },
})
</script>

<template>
    <Modal :show="show" :title="isEdit ? 'Edit Role' : 'Add Role'" @onhide="$emit('hidemodal', false)">
        <div class="modal-body scroll-y mx-lg-5">
            <form @submit.prevent="submit()" class="form">
                <div class="d-flex flex-column scroll-y" data-kt-scroll="true"
                    data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                    data-kt-scroll-offset="300px">
                    <div class="fv-row mb-10">
                        <jet-label value="Role Name" class="required" />
                        <jet-input id="name" type="text" v-model="form.role" :class="v$.form.role.$errors.length > 0
                            ? 'is-invalid'
                            : ''
                            " placeholder="Enter a role name" />
                        <div v-for="(error, index) of v$.form.role.$errors" :key="index">
                            <input-error :message="error.$message" />
                        </div>
                    </div>
                    <div class="fv-row">
                        <label class="fs-5 fw-bold form-label mb-2">Role Permissions</label>

                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <tbody class="text-gray-600 fw-semibold">
                                    <tr>
                                        <td class="text-gray-800">Administrator Access
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                title="Allows a full access to the system"></i>
                                        </td>
                                        <td>
                                            <label class="form-check form-check-custom form-check-solid me-9"
                                                style="width: fit-content;">
                                                <input class="form-check-input" type="checkbox" v-model="admin_access"
                                                    id="roles_select_all" />
                                                <span class="form-check-label" for="roles_select_all">Select
                                                    all</span>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr v-for="(menu, index) in menus" :key="index">
                                        <td class="text-red-800 text-uppercase">{{ menu.title }} </td>
                                        <td>
                                            <div class="d-flex">
                                                <label
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-8">
                                                    <input id="read" class="form-check-input" type="checkbox"
                                                        :v-model="form.permissions[index]?.read"
                                                        :checked="form.permissions[index]?.read || admin_access"
                                                        @change="(event) => handleCheckChange(event, index, 'read')" />
                                                    <span class="form-check-label">Read</span>
                                                </label>
                                                <label
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-8">
                                                    <input class="form-check-input" type="checkbox"
                                                        :v-model="form.permissions[index]?.write"
                                                        :disabled="!form.permissions[index]?.read"
                                                        :checked="form.permissions[index]?.write"
                                                        @change="(event) => handleCheckChange(event, index, 'write')" />
                                                    <span class="form-check-label">Write</span>
                                                </label>
                                                <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        :v-model="form.permissions[index]?.delete"
                                                        :disabled="!form.permissions[index]?.read"
                                                        :checked="form.permissions[index]?.delete"
                                                        @change="(event) => handleCheckChange(event, index, 'delete')" />
                                                    <span class="form-check-label">Delete</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="text-center pt-15">
                    <button type="reset" class="btn btn-light me-3" @onhide="$emit('hidemodal', false)">Discard</button>
                    <button type="submit" class="btn btn-primary" :data-kt-indicator="processing ? 'on' : 'off'">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>