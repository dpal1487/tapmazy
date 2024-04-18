
<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
export default defineComponent({
    components: {
        Link,
    },
    props: {
        links: Object,
    },
});
</script>
<template>
    <div class="row">
        <div class="col-sm-12 d-flex align-items-center justify-content-between" v-if="links.meta">
            <span class="fw-bold text-gray-700">
                Showing {{ links.meta.from }} to {{ links.meta.to }}
                of {{ links.meta.total }} entries
            </span>
            <ul class="pagination" v-if="links.meta.links.length > 3">
                <template v-for="(link, key) in links.meta.links" :key="key">
                    <li v-if="link.url === null" class="page-item disabled">
                        <span class="page-link" aria-hidden="true" v-html="link.label"></span>
                    </li>

                    <Link v-else class="page-item" :class="{ 'active': link.active }" :href="link.url">
                    <span class="page-link" aria-hidden="true" v-html="link.label"></span>
                    </Link>
                </template>
            </ul>
        </div>
    </div>
</template>

