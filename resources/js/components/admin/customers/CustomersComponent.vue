<template>
  <div>
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
        <h4>Customers</h4>
      </div>
    </div>

    <b-table
      striped
      hover
      outline
      responsive="sm"
      :items="customers"
      :fields="fields"
      :per-page="perPage"
      :current-page="currentPage"
      :busy="mute"
    >
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
function Customer({ id, name, email }) {
  this.id = id;
  this.name = name;
  this.email = email;
}

export default {
  data() {
    return {
      mute: false,
      perPage: 2,
      currentPage: 1,
      fields: [
        {
          key: "name",
          sortable: true
        },
        {
          key: "email",
          sortable: false
        }
      ],
      customers: []
    };
  },
  methods: {
    read() {
      this.mute = true;
      window.axios.get("/customers").then(({ data }) => {
        data.forEach(customer => {
          this.customers.push(new Customer(customer));
        });
        this.mute = false;
      });
    }
  },
  computed: {
    rows() {
      return this.customers.length;
    }
  },
  created() {
    this.read();
  }
};
</script>
