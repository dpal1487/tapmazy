<script>
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { required } from '@vuelidate/validators';
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import InputError from "@/jetstream/InputError.vue";
import Modal from '@/Components/Modal.vue';
import SectionLoader from '@/Components/SectionLoader.vue';
import { toast } from 'vue3-toastify';
import axios from 'axios';
import useVuelidate from '@vuelidate/core';
import { Inertia } from '@inertiajs/inertia';

export default defineComponent({
    props: ['isEdit', 'show', 'permissions', 'id'],
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
                description: {
                    required,
                }
            }
        }
    },
    data() {
        return {
            showModal: false,
            processing: false,
            isLoading: false,
            form: this.$inertia.form({
                name: '',
                id: '',
                description: '',
            }),
        }
    },
    components: {
        Modal,
        JetLabel,
        JetInput,
        InputError,
        Link,
        SectionLoader
    },
    methods: {
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.processing = true;
                axios
                    .post(this.isEdit ? route('permission.update', this.id) : route('permission.store'), this.form)
                    .then((response) => {
                        if (response?.data?.success) {
                            toast.success(response?.data?.message)
                            this.processing = false;
                            Inertia.get('permissions')
                        }
                        else {
                            toast.error(response?.data?.message)
                        }
                    });
            }
        },
    },
    async mounted() {
        if (this.id !== '') {
            if (this.isEdit == false) {
                this.form.name = '';
                this.form.description = '';
                this.form.id = '';
            }
            else {
                this.isLoading = true;
                const response = await axios.get(`/permission/${this.id}/edit`);
                this.form.name = response?.data?.permission.name;
                this.form.description = response?.data?.permission.description
                this.form.id = this.id;
            }
            this.isLoading = false;
        }
    }
})
</script>

<template>
    <Modal :show="show" :title="isEdit ? 'Edit Permission' : 'Add Permission'" @onhide="$emit('hidemodal', false)">
        <SectionLoader v-if="isLoading" :width="40" :height="40" />
        <div v-else class="mx-5">
            <form @submit.prevent="submit()" class="form">
                <div class="fv-row mb-5">
                    <jet-label for="permission-name" value="Permission Name" />
                    <jet-input v-model="v$.form.name.$model" :class="v$.form.name.$errors.length > 0
                        ? 'is-invalid'
                        : ''
                        " placeholder="Enter a permission name" />

                    <div v-for="(error, index) of v$.form.name.$errors" :key="index">
                        <input-error :message="error.$message" />
                    </div>
                </div>
                <div class="fv-row mb-5">
                    <jet-label for="permission-description" value="Permission description" />
                    <jet-input v-model="v$.form.description.$model" :class="v$.form.description.$errors.length > 0
                        ? 'is-invalid'
                        : ''
                        " placeholder="Enter a permission description" />

                    <div v-for="(error, index) of v$.form.description.$errors" :key="index">
                        <input-error :message="error.$message" />
                    </div>
                </div>
                <div class="text-center pt-15">
                    <Link href="/permissions" class="btn btn-light me-3">Discard</Link>
                    <button type="submit" class="btn btn-primary" :class="{ 'text-white-50': processing }">
                        <div v-show="processing" class="spinner-border spinner-border-sm">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span v-if="isEdit">Save Changes</span>
                        <span v-else>Save</span>
                    </button>
                </div>
            </form>
            <div v-if="form.id">
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
                        <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)"
                                    fill="currentColor" />
                                <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        <div class="d-flex flex-stack flex-grow-1">
                            <div class="fw-semibold">
                                <div class="fs-6 text-gray-700">
                                    <strong class="me-1">Warning!</strong>By editing the
                                    permission name, you might break the system permissions
                                    functionality. Please ensure you're absolutely certain
                                    before proceeding.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>
