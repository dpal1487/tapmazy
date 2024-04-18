<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import useVuelidate from "@vuelidate/core";
import { required, numeric, url } from "@vuelidate/validators";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import Modal from "@/Components/Modal.vue";
import SectionLoader from "../../../../Components/SectionLoader.vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import { toast } from "vue3-toastify";


export default defineComponent({
    setup() {
        return { v$: useVuelidate() };
    },
    props: ["project", "countries", "show", "id", "pageName"],
    components: {
        Link,
        Multiselect,
        JetInput,
        JetLabel,
        InputError,
        JetValidationErrors,
        Modal,
        SectionLoader,
    },
    validations() {
        return {
            form: {
                project_name: {
                    required,
                },
                cpi: {
                    required,
                },
                loi: {
                    required,
                    numeric,
                },
                ir: {
                    required,
                    numeric,
                },
                sample_size: {
                    required,
                    numeric,
                },
                project_link: {
                    required,
                    url,
                },
                country_id: {
                    required,
                    numeric,
                },
                status: {
                    required
                },
                target: {
                    required
                }
            },
        };
    },
    data() {
        return {
            isLoading: false,
            isStateCityLoading: false,
            isFullPage: true,
            processing: false,
            form: this.$inertia.form({
                id: '',
                project_id: this.project?.id || '',
                project_name: this.project?.project_name || '',
                cpi: this.project?.project_links?.cpi || '',
                loi: this.project?.project_links?.loi || '',
                ir: this.project?.project_links?.ir || '',
                sample_size: this.project?.project_links?.sample_size || '',
                project_link: '',
                country_id: '',
                project_state: [],
                project_city: [],
                project_zipcode: '',
                target: '',
                status: '',
                add_more: false,

            }),
            state: 0,
            states: [],
            city: 0,
            cities: [],
        };
    },
    methods: {
        async submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.processing = true;
                if (this.pageName == 'editPage') {
                    axios.post(this.route("mapping.update", this.id), this.form)
                        .then((response) => {
                            if (response.data.success) {
                                this.processing = false;
                                toast.success(response.data.message)
                                Inertia.get('/project/' + this.form.project_id)
                            } else {
                                toast.info(response.data.message)
                            }
                            if (response.data.error) {
                                toast.error(response.data.error)
                            }
                        })
                } else {
                    axios.post(this.route('mapping.store', this.project.id), this.form)
                        .then((response) => {
                            if (response.data.success) {
                                toast.success(response.data.message)
                                this.processing = false;
                                if (this.form.add_more != true) {
                                    this.$emit('hidemodal')
                                    Inertia.get('/project/' + this.project.id)
                                }
                                this.form.project_link = ''
                                this.form.country_id = ''
                            } else {
                                toast.info(response.data.message)
                            }
                            if (response.data.error) {
                                toast.error(response.data.error)
                            }
                        })
                }
            }
        },
        getStates(id) {
            axios.get('/project/state', {
                params: {
                    country_id: id
                }
            }).then((response) => {
                this.form.project_state = [];
                this.form.project_city = [];
                if (response.data?.states?.length > 0) {
                    this.states = response.data?.states;
                    this.cities = response.data?.cities;
                }
                else {
                    this.states = []
                    this.cities = []
                }
            });
        },
    },
    create() {

    },
    watch: {
        id: {
            async handler() {
                this.isLoading = true;
                const response = await axios.get(`/mapping/${this.id}/edit`);
                this.form = response?.data?.project;
                this.states = response?.data?.states;
                this.cities = response?.data?.cities;
                this.form.project_zipcode = response?.data?.project?.project_zipcode;
                this.form.project_state = response?.data?.project?.state
                this.form.project_city = response?.data?.project?.city;
                this.isLoading = false;
            }
        }
    }
});
</script>
<template>
    <Modal :show="show" @onhide="$emit('hidemodal')"
        :title="pageName == 'editPage' ? 'Edit Link Form' : 'Add New Link Form'">
        <SectionLoader v-if="isLoading" :width="40" :height="40" />
        <div v-else>
            <JetValidationErrors />
            <form @submit.prevent="submit" autocomplete="off">
                <div class="row g-5">
                    <div class="fv-row col-md-6 col-sm-12">
                        <jet-label for="project-name" value="Project Name" />
                        <jet-input id="project-name" type="text" v-model="v$.form.project_name.$model" :class="v$.form.project_name.$errors.length > 0
                            ? 'is-invalid'
                            : ''
                            " placeholder="Enter project name" />
                        <div v-for="(error, index) of v$.form.project_name.$errors" :key="index">
                            <input-error :message="error.$message" />
                        </div>
                    </div>
                    <div class="fv-row col-md-6 col-sm-12">
                        <jet-label for="project-name" value="Project Country" />
                        <Multiselect :canClear="false" :options="countries" @change='getStates' label="label" valueProp="id"
                            class="form-control form-control-solid" placeholder="Select country" :searchable="true"
                            v-model="form.country_id" :class="v$.form.country_id.$errors.length > 0
                                ? 'is-invalid'
                                : ''
                                " />
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <jet-label for="project-state" value="Project State" />
                        <Multiselect :can-clear="false" :close-on-select="false" mode="tags" id="project-state"
                            :options="states" label="name" valueProp="name" class="form-control form-control-solid"
                            placeholder="Select state" :searchable="true" v-model="form.project_state" />

                    </div>
                    <div class="col-md-6 col-sm-12">
                        <jet-label for="project-city" value="Project City" />
                        <Multiselect :can-clear="false" :close-on-select="false" mode="tags" :options="cities" label="name"
                            valueProp="name" class="form-control form-control-solid" placeholder="Select city"
                            :searchable="true" v-model="form.project_city" />
                    </div>
                    <div class="fv-row col-12">
                        <jet-label for="project-zipcode" value="Project Zipcode" />
                        {{ form.project_zipcode }}
                        <textarea class="form-control form-control-solid" v-model="form.project_zipcode"
                            id="project-zipcode" placeholder="Type project zipcode here..."></textarea>
                    </div>
                    <div class="fv-row col-12">
                        <jet-label for="project-link" value="Project Link" />
                        <jet-input id="project-link" type="text" v-model="v$.form.project_link.$model" :class="v$.form.project_link.$errors.length > 0
                            ? 'is-invalid'
                            : ''
                            " placeholder="Enter project link" />
                        <div v-for="(error, index) of v$.form.project_link.$errors" :key="index">
                            <input-error :message="error.$message" />
                        </div>

                        <div class="form-text">
                            Capture unique user id as a (<b>RespondentID</b>)
                        </div>
                    </div>
                    <div class="fv-row col-md-6 col-sm-12">
                        <jet-label for="project-link" value="Project CPC/CPI" />
                        <div class="input-group has-validation">
                            <span class="input-group-text">$</span>
                            <input type="number" step="any" class="form-control form-control-solid" id="project-cpi"
                                placeholder="Enter project CPI/CPC" v-model="v$.form.cpi.$model" :class="v$.form.cpi.$errors.length > 0
                                    ? 'is-invalid'
                                    : ''
                                    " />
                        </div>
                        <div v-for="(error, index) of v$.form.cpi.$errors" :key="index">
                            <input-error :message="error.$message" />
                        </div>
                    </div>
                    <div class="fv-row col-md-6 col-sm-12">
                        <jet-label for="project-loi" value="Project LOI" />
                        <jet-input id="project-loi" type="number" v-model="v$.form.loi.$model" :class="v$.form.loi.$errors.length > 0
                            ? 'is-invalid'
                            : ''
                            " placeholder="Enter project LOI" />
                        <div v-for="(error, index) of v$.form.loi.$errors" :key="index">
                            <input-error :message="error.$message" />
                        </div>
                    </div>
                    <div class="fv-row col-md-6 col-sm-12">
                        <jet-label for="project-ir" value="Project Incidence Rate (IR)" />
                        <jet-input id="project-ir" type="number" v-model="v$.form.ir.$model" :class="v$.form.ir.$errors.length > 0
                            ? 'is-invalid'
                            : ''
                            " placeholder="Enter incidence rate" />
                        <div v-for="(error, index) of v$.form.ir.$errors" :key="index">
                            <input-error :message="error.$message" />
                        </div>
                    </div>
                    <div class="fv-row col-md-6 col-sm-12">
                        <jet-label for="sample-size" value="Sample Size" />
                        <jet-input id="sample-size" type="number" placeholder="Enter sample size"
                            v-model="v$.form.sample_size.$model" :class="v$.form.sample_size.$errors.length > 0
                                ? 'is-invalid'
                                : ''
                                " />
                        <div v-for="(error, index) of v$.form.sample_size.$errors" :key="index">
                            <input-error :message="error.$message" />
                        </div>
                    </div>
                    <div class="fv-row col-12">
                        <jet-label for="project-notes" value="Project Notes" />
                        <textarea rows="5" class="form-control form-control-solid" v-model="form.target" id="project-notes"
                            placeholder="Type important message here..."></textarea>
                        <div v-for="(error, index) of v$.form.target.$errors" :key="index">
                            <input-error :message="error.$message" />
                        </div>
                    </div>
                    <div class="fv-row col-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="d-flex">
                                    <div class="form-check form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="radio" value="1" id="active"
                                            v-model="form.status" />
                                        <label class="form-check-label" for="active">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="radio" value="0" id="inactive"
                                            v-model="form.status" />
                                        <label class="form-check-label" for="inactive">
                                            Inactive
                                        </label>
                                    </div>
                                </div>
                                <div v-for="(error, index) of v$.form.status.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="form-check" v-if="pageName != 'editPage'">
                                <input class="form-check-input" type="checkbox" id="new-link" v-model="form.add_more" />
                                <label class="form-check-label" for="new-link">
                                    Add More Project Link
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <Link type="button" class="btn btn-secondary me-5" :href="`/project/${form.project_id}`">Discard
                        </Link>
                        <!--begin::Button-->
                        <button type="submit" class="btn btn-primary" :class="{ 'text-white-50': processing }">
                            <div v-if="processing == true" class="spinner-border spinner-border-sm">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
</template>
