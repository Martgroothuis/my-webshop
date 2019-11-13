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
      <b-modal id="newproduct" ref="newProduct" title="New Product" hide-footer>
        <form method="post" @submit.prevent="create()">
          <input type="hidden" name="_token" :value="csrf" />
          <b-form-group id="input-group-price" label="Name:" label-for="price">
            <b-form-input v-model="name" type="text" name="name" required placeholder="Enter Name"></b-form-input>
          </b-form-group>
          <b-button block type="submit" variant="primary">Submit</b-button>
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

        <b-button
          size="sm"
          variant="primary"
          @click="edit(row.item.id, row.item.name)"
          class="mr-2"
        >Edit</b-button>
      </template>

      <template v-slot:table-busy>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </template>
    </b-table>
    <b-modal id="editproduct" ref="editproduct" title="Edit Product" hide-footer>
      <form method="post" @submit.prevent="update(selectedProduct.id, name)">
        <input type="hidden" name="_token" :value="csrf" />
        <b-form-group id="input-group-name" label="Name:" label-for="name">
          <b-form-input v-model="name" id="name" type="text" required placeholder="Enter new name"></b-form-input>
        </b-form-group>
        <b-button block type="submit" variant="primary">Submit</b-button>
      </form>
    </b-modal>
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
      products: [],
      selectedProduct: []
    };
  },
  methods: {
    select(id, name) {
      this.$emit("select", id, name);
    },
    create() {
      console.log(this.name);
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
          this.name = "";

          this.products.push(new Product(data));
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
    edit(id, name) {
      this.selectedProduct.id = id;
      this.selectedProduct.name = name;
      this.name = name;
      this.$refs["editproduct"].show();
    },
    update(id, name) {
      window.axios.put(`/api/products/${id}`, { name }).then(() => {
        this.$emit(
          "toast",
          "warning",
          "Update",
          "Product: " + name + " Updated"
        );
        this.name = "";
        this.$refs["editproduct"].hide();
        this.products.find(product => product.id === id).name = name;
      });
    },
    del(id, name) {
      window.axios.delete(`/api/products/${id}`).then(() => {
        this.$emit(
          "toast",
          "danger",
          "Deletion",
          "Product: " + name + " Deleted"
        );
        let index = this.products.findIndex(product => product.id === id);
        this.products.splice(index, 1);
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
