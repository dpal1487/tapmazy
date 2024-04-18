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
    props: ["client", "allowed_clients", 'clients'],
    setup() {
        return { v$: useVuelidate() };
    },
    validations() {
        return {
            states: [],
            form: {
                client_name: {
                    required,
                },
                display_name: {
                },
                tax_number: {
                },
                description: {
                },
                website: {
                    required,
                },
                linkedin_profile: {
                },
                skype_profile: {
                    required,
                },

            },
        };
    },
    data() {
        return {
            isEdit: false,
            title: '',
            form: this.$inertia.form({
                id: this.client?.data?.id,
                client_name: this.client?.data?.name || "",
                display_name: this.client?.data?.display_name || "",
                tax_number: this.client?.data?.tax_number || "",
                website: this.client?.data?.website || "",
                linkedin_profile: this.client?.data?.linkedin_profile || "",
                skype_profile: this.client?.data?.skype_profile || "",
                description: this.client?.data?.description || "",
            }),
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
                    .post(route().current() == 'client.create' ? this.route("client.store") : this.route("client.update", this.form.id),
                        {
                            onSuccess: (data) => {
                                toast.success(this.$page.props.jetstream.flash.message);
                            },
                            onError: (data) => {
                                console.log(data);
                                // toast.error(data.message)
                            },
                        }
                    );
            }
        },
    },
    created() {
        if (route().current() == 'client.edit') {
            this.isEdit = true;
            this.title = "Client Edit"
        }
        else {

            this.title = "Add new Client";
        }
    }
});
</script>
<template>
    <Head :title="isEdit ? 'Edit Client' : `Add New Client`" />
    <AppLayout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <Link :href="`/clients`" class="text-muted text-hover-primary">
                Clients
                </Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                Client Form
            </li>
        </template>
        <div class="d-flex flex-column flex-lg-row flex-column-fluid justify-content-center">
            <div class="col-12">
                <div v-if="clients > allowed_clients?.data?.allowed_clients">
                    <PlanWarning :data="allowed_clients?.data" />
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
                                    <div class="fv-row col-md-6 col-sm-12">
                                        <jet-label for="client-name" value="Client Name" />
                                        <jet-input id="client-name" type="text" v-model="v$.form.client_name.$model" :class="v$.form.client_name.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " placeholder="Enter client full name" />
                                        <div v-for="(error, index) of v$.form.client_name.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6 col-sm-12">
                                        <jet-label for="display-name" value="Display Name" />
                                        <jet-input id="display-name" type="text" v-model="v$.form.display_name.$model"
                                            :class="v$.form.display_name.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Enter display name" />
                                        <div v-for="(error, index) of v$.form.display_name.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <jet-label for="web-site" value="Web Site" />
                                        <jet-input id="web-site" type="text" v-model="v$.form.website.$model" :class="v$.form.website.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " placeholder="Client web site" />
                                        <div v-for="(error, index) of v$.form.website.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <jet-label for="linkedin-profile" value="LinkedIn profile" />
                                        <jet-input id="linkedin-profile" type="text"
                                            v-model="v$.form.linkedin_profile.$model" :class="v$.form.linkedin_profile.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Enter LinkedIn profile" />
                                        <div v-for="(error, index) of v$.form.linkedin_profile.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <jet-label for="skypy-profile" value="Skypy profile" />
                                        <jet-input id="skypy-profile" type="text" v-model="v$.form.skype_profile.$model"
                                            :class="v$.form.skype_profile.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Enter skypy profile" />
                                        <div v-for="(error, index) of v$.form.skype_profile.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label for="display-name" value="Tax number" />
                                        <jet-input id="display-name" type="text" v-model="v$.form.tax_number.$model" :class="v$.form.tax_number.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " placeholder="Enter Tax number" />
                                        <div v-for="(error, index) of v$.form.tax_number.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row mb-3">
                                        <jet-label for="description " value="Description " />
                                        <textarea class="form-control form-control-lg form-control-solid" id="description "
                                            type="text" v-model="v$.form.description.$model" :class="v$.form.description.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Enter description " />
                                        <div v-for="(error, index) of v$.form.description.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 gap-5">
                                <div class="d-flex justify-content-end gap-5">
                                    <Link href="/clients" class="btn btn-outline-secondary">
                                    Discard
                                    </Link>
                                    <button type="submit" class="btn btn-primary"
                                        :class="{ 'text-white-50': form.processing }">
                                        <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span v-if="(route().current() == 'client.create')">
                                            Save
                                        </span>
                                        <span v-else>
                                            Save Changes
                                        </span>
                                    </button>
                                    <!--end::Button-->
                                </div>
                            </div>
                        </div>
                        <!--end::Actions-->
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
