<template>
    <draggable
        :list="items"
        :group="{ name: 'g1' }"
        animation="200"
        scrollSensitivity="150"
        scrollSpeed="20"
        forceFallback="false"
        class="dragArea"
        @end="updateOrder"
        >
            <div v-for="(el, index) in items" :key="index" class="card ml-3">
                <div class="card-body">
                    <div class="form-inline">
                        <label class="mr-2" :for="'label-'+ el.id + el.order">ID: {{ el.id }} - order: {{ el.order }}</label>
                        <input :id="'label-'+ el.id + el.order" type="text" class="form-control mr-2" v-model="el.name">
                        <button @click="addItem" class="btn btn-primary mr-2">Add</button>
                        <button @click="deleteItem(index, el.id)" class="btn btn-danger mr-2">Delete</button>
                    </div>
                </div>
                <nested-draggable :items="el.items" />
            </div>
    </draggable>
</template>
<script>
import draggable from "vuedraggable";
import { EventBus } from '../event-bus'

export default {
    name: "nested-draggable",
    props: {
        items: {
            required: true,
            type: Array
        }
    },
    components: {
        draggable
    },
    computed: {
        itemsLength() {
            return this.items.length
        }
    },
    methods: {
        addItem() {
            this.items.push({
                id: null,
                parent_id: null,
                order: this.itemsLength,
                name: "new item",
                items: []
            })
        },
        deleteItem(index, id) {
            this.items.splice(index, 1);
            EventBus.$emit('delete_item', id);
        },
        updateOrder() {
            EventBus.$emit('update_order', true);
        }
    }
};
</script>
<style scoped>
.dragArea {
  min-height: 78px;
  background-color: #eee;
  padding-bottom: 5px;
  border: 1px dashed #222;
}
</style>
