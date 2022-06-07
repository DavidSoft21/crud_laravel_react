import React  from 'react';
import { Link } from 'react-router-dom';

class Listar extends React.Component {

    constructor(props){
        super(props);
        this.state = {
            productosCargados: false,
            productos:[]
        }
    }

    cargarDatos(){

        fetch(`${process.env.REACT_APP_API}/product_index`)
        .then(resultado => resultado.json())
        .then((data)=>{
            this.setState({ productosCargados:true , productos:data});
            console.log(data);
        })
        .catch(console.log);
    }

    eliminarDatos(id) {
        const requestOptions = {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' }
        };
        fetch(`${process.env.REACT_APP_API}/product_delete/${id}`, requestOptions)
        .then(resultado => resultado.json())
        .then((data) => {
            console.log(data);
            this.cargarDatos();
        })
        .catch(console.log);
    }



    componentDidMount(){
        this.cargarDatos();
    }
    
    render(){
        const { productosCargados } = this.state;
        const { productos } = this.state;
        if (!productosCargados){
            return(<div>Cargando...</div>)
        }else{
            return (
                <div className="card text-center">
                    <div className="card-header">Productos</div>
                    <div className="card-body">
                        <table className="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">CATEGORIA</th>
                                    <th scope="col">NOMBRE</th>
                                    <th scope="col">REFERENCIA</th>
                                    <th scope="col">PRECIO</th>
                                    <th scope="col">STOCK</th>
                                    <th scope="col">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                {
                                    productos.map((product) => (
                                        <tr key={product.id}>
                                            <th scope="row">  {product.id} </th>
                                            <td> {product.category} </td>
                                            <td> {product.name} </td>
                                            <td> {product.reference} </td>
                                            <td> {product.price} </td>
                                            <td> {product.stock} </td>
                                            <td>
                                                <Link className="btn btn-primary" to={`/editar/${product.id}`}>editar</Link>
                                                <button className="btn btn-danger" onClick={() => { this.eliminarDatos(product.id)}}>eliminar</button>
                                            </td>
                                        </tr>
                                    ))
                                }
                            </tbody>
                        </table>
                    </div>
                </div>
            )
        }
        
    }

}

export default Listar;