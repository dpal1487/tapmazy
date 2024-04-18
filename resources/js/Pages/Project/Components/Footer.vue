<script>
import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
export default defineComponent({
    props: ["id"],
    data() {
        return {
            links: [
                { label: 'Overview', link: '/project/' + this.id ,name:'project.show'},
                { label: 'Suppliers', link: '/project/' + this.id + '/suppliers',name:'project.suppliers' },
            ],
        };
    },
    components: {
        Link,
        Head,
    },
});
</script>
<template>
    <ul class="nav nav-stretch nav-line-tabs border-top fs-5 fw-bold">
        <li class="nav-item" v-for="(link, index) in links" :key="index">
            <Link class="nav-link text-active-primary ms-0 me-10 py-5"
                :class="route().current() == link.name ? 'active' : ''" :href="link.link">
            {{ link.label }}</Link>
        </li>
    </ul>
</template>