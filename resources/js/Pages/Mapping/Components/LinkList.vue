<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import SupplierListItem from "./SupplierListItem.vue";
import SupplierListModel from "./Modal/SupplierListModel.vue";
import CopyLinkButton from "../../../Components/CopyLinkButton.vue";
import ProjectLinkForm from "../../Project/Components/Modal/ProjectLinkForm.vue";
import utils from "../../../utils";
export default defineComponent({

    props: ["links", "project", "countries"],
    data() {
        return {
            isLoading: false,
            isFullPage: true,
            isModalOpen: false,
            isFormModalOpen: false,
            activeId: null,
            projectId: null,
            pageName: null
        };
    },
    components: {
        Link,
        Loading,
        SupplierListModel,
        CopyLinkButton,
        SupplierListItem,
        ProjectLinkForm
    },
    methods: {
        async confirmDelete(index) {
            await utils.deleteIndexDialog(
                route("mapping.destroy", this.links[index].id),
                this.links,
                index
            );
        },
        showSupplierListModal(id) {
            this.isModalOpen = true;
            this.activeId = id;
        },
        showProjectLinkForm(value) {
            this.isFormModalOpen = true;
            this.projectId = value.id;
            this.pageName = value.pageName;
        },
        hideSupplierListModal() {
            this.isModalOpen = false;
        },
        hideProjectLinkForm() {
            this.isFormModalOpen = false;
        },


    },
    created() { },
});
</script>
<template>
    <loading :active="isLoading" :can-cancel="true" :is-full-page="isFullPage"></loading>
    <SupplierListModel :show="isModalOpen" @hidemodal="hideSupplierListModal" :id="activeId" />
    <ProjectLinkForm :show="isFormModalOpen" @hidemodal="hideProjectLinkForm" :id="projectId" :countries="countries"
        :pageName="pageName" />
    <div class="card mb-5" v-for="(project, index) in links" :key="index">
        <SupplierListItem :index="index" :project="project" @onSupplier="showSupplierListModal"
            @editProjectLink="showProjectLinkForm" @onDelete="confirmDelete" />
    </div>
</template>
