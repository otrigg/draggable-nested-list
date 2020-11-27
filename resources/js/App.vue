<template>
    <div>
        <div class="row py-2">
            <div class="col-12">
                <div @click="addItem" class="btn btn-primary">Add</div>
                <div @click="save" class="btn btn-success">Save changes</div>
            </div>
        </div>
        <div class="row py-2">
            <div class="col-12">
                <nested-draggable :items="list"></nested-draggable>
            </div>
        </div>
        <div class="row py-2">
            <div class="col-12">
                <div @click="addItem" class="btn btn-primary">Add</div>
                <div @click="save" class="btn btn-success">Save changes</div>
            </div>
        </div>
        <notifications position="bottom"></notifications>
    </div>
</template>
<script>
import axios from "axios";
import nestedDraggable from "./components/nested";
import { EventBus } from "./event-bus";

export default {
    data() {
        return {
            list: [
                {
                    id: null,
                    parent_id: null,
                    order: 0,
                    name: "new item",
                    items: []
                }
            ],
            apiUrl: "/api/list"
        };
    },
    components: {
        nestedDraggable
    },
    computed: {
        listLength() {
            return this.list.length;
        }
    },
    methods: {
        getList() {
            axios
                .get(window.location.origin + this.apiUrl)
                .then(response => {
                    this.list = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        save() {
            if (this.listLength > 0) {
                axios
                    .post(window.location.origin + this.apiUrl, this.list)
                    .then(response => {
                        this.getList();
                        this.$notifySuccess("Items saved");
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
        deleteItem(id) {
            if (id) {
                axios
                    .delete(window.location.origin + this.apiUrl, {
                        data: {
                            id: id
                        }
                    })
                    .then(response => {
                        this.getList();
                        this.updateListOrder(this.list);
                        this.$notifyError("Item deleted");
                        this.save();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
        addItem() {
            this.list.push({
                id: null,
                parent_id: null,
                order: this.listLength,
                name: "new item",
                items: []
            });
        },
        updateListOrder(items) {
            let i = 0;
            items.forEach(element => {
                element.order = i;
                this.updateListOrder(element.items);
                i++;
            });
        }
    },
    mounted() {
        this.getList();
        EventBus.$on("update_order", () => {
            this.updateListOrder(this.list);
        });
        EventBus.$on("delete_item", id => {
            this.deleteItem(id);
            this.updateListOrder(this.list);
        });
    }
};
</script>
