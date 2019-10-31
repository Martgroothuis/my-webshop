<template>
  <div>
    <div class="d-flex justify-content-between mb-4">
      <div class="d-flex justify-content-start">
        <div>
          <b-pagination
            class="mb-0"
            v-model="currentPage"
            :total-rows="rows"
            :per-page="perPage"
            aria-controls="my-table"
          ></b-pagination>
        </div>
        <div class="m-1 ml-3">
          <h4>Products</h4>
        </div>
      </div>

      <b-button variant="primary" v-b-modal.newproduct>New Product</b-button>
      <b-modal id="newproduct" ref="newProduct" title="New Product">
        <form method="post" @submit.prevent="create()">
          <input type="hidden" name="_token" :value="csrf" />
          <input class="form-control" v-model="name" type="text" name="name" />
          <b-button type="submit" variant="primary">Submit</b-button>
        </form>
      </b-modal>
    </div>

    <b-table
      striped
      hover
      outline
      responsive="sm"
      :items="products"
      :fields="fields"
      :per-page="perPage"
      :current-page="currentPage"
      :busy="mute"
    >
      <template v-slot:cell(actions)="row">
        <b-button
          size="sm"
          variant="danger"
          @click="del(row.item.id, row.item.name)"
          class="mr-2"
        >Delete</b-button>

        <b-button
          size="sm"
          variant="info"
          @click="select(row.item.id, row.item.name)"
          class="mr-2"
        >Select</b-button>
      </template>

      <template v-slot:table-busy>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </template>
    </b-table>
  </div>
</template>

<script>
function Product({ id, name }) {
  this.id = id;
  this.name = name;
}

export default {
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),

      product: 2,
      mute: false,
      perPage: 10,
      currentPage: 1,
      name: "",
      fields: [
        {
          key: "id",
          sortable: true
        },
        {
          key: "name",
          sortable: true
        },
        "actions"
      ],
      products: []
    };
  },
  methods: {
    select(id, name) {
      this.$emit("select", id, name);
    },
    openModal(id) {
      this.product = id;
      console.log(this.product);
      this.$refs["productVersions"].show();
    },
    create() {
      console.log(this.name);
      this.mute = true;
      this.$refs["newProduct"].hide();

      window.axios
        .post("/api/products", {
          name: this.name
        })
        .then(({ data }) => {
          this.$emit(
            "toast",
            "success",
            "Create",
            "Product: " + this.name + " Created"
          );

          this.products.push(new Product(data));
          this.mute = false;
        });
    },
    read() {
      this.mute = true;
      window.axios.get("/api/products").then(({ data }) => {
        data.forEach(product => {
          this.products.push(new Product(product));
        });
        this.mute = false;
      });
    },
    update(id, name) {
      this.mute = true;
      window.axios.put(`/api/products/${id}`, { name }).then(() => {
        this.$emit(
          "toast",
          "waring",
          "Update",
          "Product: " + name + " Updated"
        );

        this.products.find(product => product.id === id).name = name;
        this.mute = false;
      });
    },
    del(id, name) {
      this.mute = true;
      window.axios.delete(`/api/products/${id}`).then(() => {
        this.$emit(
          "toast",
          "danger",
          "Deletion",
          "Product: " + name + " Deleted"
        );

        let index = this.products.findIndex(product => product.id === id);
        this.products.splice(index, 1);
        this.mute = false;
      });
    }
  },
  computed: {
    rows() {
      return this.products.length;
    }
  },
  created() {
    this.read();
  }
};
</script>
