import { Line, mixins } from 'vue-chartjs'


    
    export default {
       extends: Line,
      data () {
        return {
          gradient: null,
          gradient2: null,
         
        }
      },
      created(){
        axios.get("api/metacredito").then(({ data }) => { 
          
          this.renderChart({


          labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
          datasets: [
            {
              label: 'Meta',
              borderColor: '#F96607',
              pointBackgroundColor: 'white',
              borderWidth: 1,
              pointBorderColor: 'white',
              backgroundColor: this.gradient,
              data: data.valor1,
            },{
              label: 'Creditos',
              borderColor: '#05CBE1',
              pointBackgroundColor: 'white',
              pointBorderColor: 'white',
              borderWidth: 1,
              backgroundColor: this.gradient2,
              data: data.valor2
            }
          ]
        }, {responsive: true, maintainAspectRatio: false})

        }).catch(error => { this.error = error.response });
      },
      mounted () {
        this.gradient = this.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450)
        this.gradient2 = this.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450)
    
        this.gradient.addColorStop(0, 'rgba(255, 131,51, 0.5)')
        this.gradient.addColorStop(0.5, 'rgba(255, 131, 51, 0.25)');
        this.gradient.addColorStop(1, 'rgba(255, 131, 51, 0)');


        //this.gradient.addColorStop(0, 'rgba(255, 0,0, 0.5)')
        //this.gradient.addColorStop(0.5, 'rgba(255, 0, 0, 0.25)');
        //this.gradient.addColorStop(1, 'rgba(255, 0, 0, 0)');
        //255, 131, 51
        
        this.gradient2.addColorStop(0, 'rgba(0, 231, 255, 0.9)')
        this.gradient2.addColorStop(0.5, 'rgba(0, 231, 255, 0.25)');
        this.gradient2.addColorStop(1, 'rgba(0, 231, 255, 0)');
        
        
        
    
      }
    }