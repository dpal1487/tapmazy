<script>
import { defineComponent } from 'vue';
import AppLayout from '../../Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import FaqForm from './Components/FaqForm.vue';
import Header from './Components/Header.vue';
import { toast } from 'vue3-toastify';
export default defineComponent({
    props: ['employees', 'faqs'],
    emits: ["submitted"],

    data() {
        return {
            isEdit: false,
            isAdd: false,
            title:'FAQ',
            form: {
                processing: false,
            },
        }
    },
    components: {
        AppLayout,
        Link,
        FaqForm,
        Header
    },
    methods: {
        submit(form) {

            this.form = form;
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(this.isEdit ? this.route("support.faq.update", this.faq.data.id) : this.route("support.faq.create"), {
                    onSuccess: (data) => {
                        toast.success(this.$page.props.jetstream.flash.message);
                        this.isEdit = false;
                        this.isAdd = false;
                    },
                    onError: (data) => {
                        toast.error(data.message);

                    },
                });
        },
    }
})
</script>


<template>
    <AppLayout :title="title">
        <Header :employees="employees.data" />
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/support" class="text-muted text-hover-primary">Support</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/support/faq" class="text-muted text-hover-primary">Faq</Link>
            </li>

        </template>
        <div class="card">
            <div class="card-header cursor-pointer">
                <div class="card-title mt-5">
                    <h4 class="fs-2x text-gray-800 w-bolder mb-6">Frequesntly Asked Questions</h4>
                </div>
                <button class="btn btn-primary align-self-center" v-if="!isEdit"
                    @click="this.isAdd = true, this.form = {}"><i class="bi bi-plus-circle"></i>Add A New FAQ
                </button>
                <p class="fw-semibold fs-4 text-gray-600 mb-2">First, a disclaimer – the entire process of writing a
                    blog post often takes more than a couple of hours, even if you can type eighty words as per
                    minute and your writing skills are sharp.</p>
            </div>
            <div class="card-body p-10 p-lg-15">
                <div class="row mb-12" v-if="isEdit || isAdd">
                    <FaqForm @submitted="submit">
                        <template #action>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-secondary me-5"
                                    @click="this.isEdit = false, this.isAdd = false">Discard</button>
                                <button type="submit" class="btn btn-primary" :class="{ 'text-white-50': form.processing }">
                                    <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    Save Changes
                                </button>
                            </div>
                        </template>
                    </FaqForm>
                </div>
                <div class="row mb-12" v-else>
                    <div class="col-md-6 pe-md-10 mt-5 mb-md-0" v-for="(faq, index) in faqs?.data" :key="index">
                        <h2 class="text-gray-800 fw-bold mb-4 text-capitalize">{{ faq?.title }}</h2>
                        <div class="m-0">
                            <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0"
                                data-bs-toggle="collapse" :data-bs-target="`#faq${faq.id}`">
                                <div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
                                    <span class="svg-icon toggle-on svg-icon-primary svg-icon-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                                fill="currentColor" />
                                            <rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <span class="svg-icon toggle-off svg-icon-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                                fill="currentColor" />
                                            <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                                transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                                            <rect x="6.01041" y="10.9247" width="12" height="2" rx="1"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                </div>
                                <h4 class="text-gray-700 fw-bold cursor-pointer mb-0">{{ faq?.category }}</h4>
                            </div>
                            <div class=" fs-6 ms-1">
                                <div :id="`faq${faq.id}`" class="mb-4 collapse text-gray-600 fw-semibold fs-6 ps-10"
                                    v-for="(category) in faq.category">{{
                                        category.artical }}.</div>
                            </div>
                            <div class="separator separator-dashed"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
