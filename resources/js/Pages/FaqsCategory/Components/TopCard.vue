<script>
import { defineComponent } from "vue";
export default defineComponent({
    props: ["faq_category"],
});
</script>
<style>
.rhombus {
    clip-path: polygon(50% 0%, 0% 50%, 50% 100%, 100% 50%);
    width: 150px;
    height: 150px;
    display: grid;
    place-content: center;
}

.drop-shadow {
    filter: drop-shadow(0 0 20px rgb(204, 253, 230));
}
</style>
<template>
    <div class="mb-5">
        <div class="card">
            <div class="card-header align-items-center">
                <div class="card-title">
                    <h2>FAQs Category</h2>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="row m-7 align-items-center">
                            <label class="col-lg-4  fw-bold fs-5 text-gray-700">Image</label>
                            <div class="col-lg-8">
                                <div v-if="faq_category.image">
                                    <div class="symbol symbol-160px me-5">
                                        <img v-if="faq_category?.image" :src="faq_category?.image?.url" alt="image"
                                            class="rounded" />
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="symbol symbol-60px me-5">
                                        <img alt="Logo" src="/assets/media/svg/avatars/blank.svg">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-7">
                            <label class="col-lg-4 fw-bold fs-5 text-gray-700">Title</label>
                            <div class="col-lg-8">
                                <div class="fw-bold fs-6 text-gray-500 text-capitalize">{{ faq_category.title }} </div>
                            </div>
                        </div>
                        <div class="row m-7">
                            <label class="col-lg-4 fw-bold fs-5 text-gray-700">Status</label>
                            <div class="col-lg-8">
                                <div class="fw-bold fs-6 text-capitalize badge badge-light-success rounded-pill"
                                    v-if="this.faq_category?.status == 1">
                                    Published</div>
                                <div class="fw-bold fs-6 text-capitalize badge badge-light-danger rounded-pill" v-else>
                                    Unpublished</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="h-100 d-flex justify-content-center align-items-center bg-gray-100">
                            <div class="drop-shadow">
                                <div class="text-center bg-white p-20 rhombus">
                                    <div>
                                        <h1>{{ faq_category?.header?.total }}</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
