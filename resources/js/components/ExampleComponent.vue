<template>
  <div class="overflow-auto">
    <b-container>
      <b-pagination
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        aria-controls="my-table"
      ></b-pagination>

      <p class="mt-3">Current Page: {{ currentPage }}</p>

      <b-table
        striped
        hover
        outline
        :items="items"
        :fields="fields"
        :per-page="perPage"
        :current-page="currentPage"
      ></b-table>

      <div>
        <b-card no-body>
          <b-tabs card>
            <b-tab title="Tab 1" active>
              <b-img-lazy
                class="w-100"
                center
                src="https://wallpapercave.com/wp/wp1848524.jpg"
                blank-src="https://picsum.photos/id/25/200/100"
                offset="0"
              ></b-img-lazy>
            </b-tab>
            <b-tab title="Tab 2">
              <b-img-lazy
                class="w-100"
                center
                src="https://picsum.photos/seed/picsum/3000/2000"
                blank-src="https://picsum.photos/seed/picsum/30/20?blur"
                offset="0"
              ></b-img-lazy>
            </b-tab>
            <b-tab title="Tab 3">
              <b-img-lazy
                class="w-100"
                center
                src="https://picsum.photos/id/25/3200/1800"
                blank-src="https://picsum.photos/id/25/160/90"
                offset="0"
              ></b-img-lazy>
            </b-tab>
          </b-tabs>
        </b-card>
      </div>
    </b-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      perPage: 3,
      currentPage: 1,
      fields: [
        {
          key: "last_name",
          sortable: true
        },
        {
          key: "first_name",
          sortable: true
        }
      ],
      items: [
        { id: 1, first_name: "Fred", last_name: "Flintstone" },
        { id: 2, first_name: "Wilma", last_name: "Flintstone" },
        { id: 3, first_name: "Barney", last_name: "Rubble" },
        { id: 4, first_name: "Betty", last_name: "Rubble" },
        { id: 5, first_name: "Pebbles", last_name: "Flintstone" },
        { id: 6, first_name: "Bamm Bamm", last_name: "Rubble" },
        { id: 7, first_name: "The Great", last_name: "Gazzoo" },
        { id: 8, first_name: "Rockhead", last_name: "Slate" },
        { id: 9, first_name: "Pearl", last_name: "Slaghoople" }
      ]
    };
  },
  computed: {
    rows() {
      return this.items.length;
    }
  }
};
</script>
