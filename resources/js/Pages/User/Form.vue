<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { toast } from "vue3-toastify";
import PlanWarning from "../../Components/PlanWarning.vue";

export default defineComponent({
    props: ["user", "role", "users", "allowed_users", "userHasRoles"],
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
                role: {
                    required
                },
                password: {
                },
                email: {
                    required,
                },
                status: {
                    required,
                },
            },
        };
    },
    data() {
        return {
            isEdit: false,
            form: this.$inertia.form({
                id: this.user?.data?.id,
                first_name: this.user?.data?.first_name || "",
                last_name: this.user?.data?.last_name || "",
                email: this.user?.data?.email || "",
                role: this.user?.data?.role?.id || "",
                password: this.user?.data?.password || "",
                status: this.user?.data?.status || 1,
            }),
            status: [
                { name: 'Active', id: '1' },
                { name: 'Inactive', id: '0' },
            ]
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        Multiselect,
        JetInput,
        JetLabel,
        InputError,
        JetValidationErrors,
        PlanWarning
    },
    methods: {
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.form
                    .transform((data) => ({
                        ...data,
                    }))
                    .post(route().current() == 'user.create' ? this.route("user.store") : this.route("user.update", this.form.id),
                        {
                            onSuccess: (data) => {
                                toast.success(this.$page.props.jetstream.flash.message);
                            },
                            onError: (data) => {
                                toast.error(data.message)
                            },
                        }
                    );
            }
        },
    },
    created() {
        if (route().current() == 'user.edit') {
            this.isEdit = true;
            this.title = "User Edit"
        }
        else {

            this.title = "Add New User";
        }
    }
});
</script>
<template>
    <Head :title="isEdit ? 'Edit User' : `Add New User`" />
    <AppLayout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <Link :href="`/users`" class="text-muted text-hover-primary">
                Users
                </Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                {{ title }}
            </li>
        </template>
        <div class="d-flex flex-column flex-lg-row flex-column-fluid justify-content-center">
            <div class="col-8">
                <div v-if="users > allowed_users?.data?.allowed_users">
                    <PlanWarning :data="allowed_users?.data" />
                </div>
                <div v-else>
                    <JetValidationErrors />
                    <form @submit.prevent="submit()" autocomplete="off"
                        class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>General</h2>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-5">
                                    <div class="fv-row col-md-6">
                                        <jet-label for="first-name" value="First Name" />
                                        <jet-input id="first-name" type="text" v-model="v$.form.first_name.$model" :class="v$.form.first_name.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " placeholder="Enter First Name" />
                                        <div v-for="(error, index) of v$.form.first_name.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label for="last-name" value="Last Name" />
                                        <jet-input id="last-name" type="text" v-model="v$.form.last_name.$model" :class="v$.form.last_name.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " placeholder="Enter Last name" />
                                        <div v-for="(error, index) of v$.form.last_name.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <jet-label for="email" value="Email" />
                                        <jet-input id="email" type="text" v-model="v$.form.email.$model" :class="v$.form.email.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " placeholder="Enter email address" />
                                        <div v-for="(error, index) of v$.form.email.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label for="role" value="Role" />
                                        <Multiselect :can-clear="false" :options="role.roles" label="name" valueProp="id"
                                            :class="v$.form.role.$errors.length > 0 ? 'is-invalid form-control form-control-lg form-control-solid' : 'form-control form-control-lg form-control-solid'"
                                            placeholder="Select Role" v-model="form.role" track-by="name"
                                            :searchable="true" />
                                        <div v-for="(error, index) of v$.form.role.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <jet-label for="password" value="Password" />
                                        <jet-input id="password" type="password" v-model="v$.form.password.$model" :class="v$.form.password.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " placeholder="Enter password" />
                                        <div v-for="(error, index) of v$.form.password.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label value="Status" />
                                        <Multiselect :can-clear="false" :options="status" label="name" valueProp="id"
                                            :class="v$.form.status.$errors.length > 0 ? 'is-invalid form-control form-control-lg form-control-solid' : 'form-control form-control-lg form-control-solid'"
                                            placeholder="Select status" v-model="form.status" track-by="name"
                                            :searchable="true" />
                                        <div v-for="(error, index) of v$.form.status.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-5">
                                    <Link href="/users" class="btn btn-outline-secondary">
                                    Discard
                                    </Link>
                                    <button type="submit" class="btn btn-primary"
                                        :class="{ 'text-white-50': form.processing }">
                                        <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span v-if="(route().current() == 'user.create')">
                                            Save
                                        </span>
                                        <span v-else>
                                            Save Changes
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
