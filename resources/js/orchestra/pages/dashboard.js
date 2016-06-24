import App from '../app'

const Dashboard = App.extend({
  data() {
    return {
      dash: {},
      pane: {},
      sidebar: {
        active: 'home'
      }
    }
  },
})

export default Dashboard
