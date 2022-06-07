import React from 'react';
import { Link, Navigate, useParams } from 'react-router-dom';

export function withRouter(Children){
    return (props)=>{
        const match = { params: useParams()};
        return <Children {...props} match= {match}/>;
    }
}

class Editar extends React.Component {


    constructor(props) {
        super(props);
        this.state = {
            id: "",
            category: "",
            name: "",
            reference: "",
            price: "",
            stock: "",
            send: false
        }
    }

    cambioValor = (e) => {
        const state = this.state;
        state[e.target.name] = e.target.value;
        this.setState({ state });
    }

    cargarDatos(id) {

        fetch(`${process.env.REACT_APP_API}/product/${id}`)
            .then(resultado => resultado.json())
            .then((data) => {
                this.setState({ 
                    productosCargados: true, 
                    id: id,
                    category: data.category,
                    name: data.name,
                    reference: data.reference,
                    price: data.price,
                    stock: data.stock,
                });
                console.log(data);
            })
            .catch(console.log);
    }

    enviarDatos = (e) => {

        e.preventDefault();

        const { category, id, name, reference, price, stock } = this.state;
        console.log(category);
        console.log(name);
        console.log(reference);
        console.log(price);
        console.log(stock);
        let data = {
            category: category,
            name: name,
            reference: reference,
            price: parseFloat(price),
            stock: parseInt(stock)
        };

        const requestOptions = {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        };

        fetch(`${process.env.REACT_APP_API}/product_update/${id}`, requestOptions)
            .then(resultado => resultado.json())
            .then((resultado) => {
                console.log(resultado);
                this.setState({ send: true });

            }).catch(console.log);
    }

    componentDidMount() {
        const id = this.props.match.params.id;
        console.log(this.props.match.params.id);
        this.cargarDatos(id);
        
    }

    render() { 

        const { send, id,category, name, reference, price, stock } = this.state;

        if (send === true) {
            return <Navigate to="/" />;
        }

        return (  
            <div className="card">
                <h5 className="card-header">Editar Producto</h5>
                <div className="card-body">
                    <form onSubmit={this.enviarDatos}>
                        <div className="mb-3">
                            <label htmlFor="id" className="form-label">ID:</label>
                            <input disabled name="id" value={id} onChange={this.cambioValor} type="text" placeholder="id" className="form-control" id="id" aria-describedby="id" />
                        </div>

                        <div className="mb-3">
                            <label htmlFor="name" className="form-label">Nombre:</label>
                            <input name="name" value={name} onChange={this.cambioValor} type="text" placeholder="nombre" className="form-control" id="name" aria-describedby="nombre"/>
                        </div>

                        <div className="mb-3">
                            <label htmlFor="category" className="form-label">Categoria:</label>
                            <select name="category" value={category} onChange={this.cambioValor} className="mb-3 form-select" aria-label="select category">
                                <option value="">Seleccionar...</option>
                                <option value="bebidas">bebidas</option>
                                <option value="panadería">panadería</option>
                                <option value="pastelería">pastelería</option>
                                <option value="postres">postres</option>
                                <option value="tortas">tortas</option>
                            </select>
                        </div>

                        <div className="mb-3">
                            <label htmlFor="reference"  className="form-label">Reference:</label>
                            <select name="reference" value={reference} onChange={this.cambioValor} className="mb-3 form-select" aria-label="select reference">
                                <option value="">Seleccionar...</option>
                                <option value="250ml">250ml</option>
                                <option value="500ml">500ml</option>
                                <option value="600ml">600ml</option>
                                <option value="1l">1l</option>
                                <option value="1.5l">1.5l</option>
                                <option value="und[s]">und[s]</option>
                                <option value="libra[s]">libra[s]</option>
                                <option value="kilo[s]">kilo[s]</option>
                            </select>
                        </div>

                        <div className="mb-3">
                            <label htmlFor="stock" className="form-label">Stock:</label>
                            <input type="number" value={stock} onChange={this.cambioValor} placeholder="stock" className="form-control" id="stock" name="stock" aria-describedby="stock"/>
                        </div>

                        <div className="mb-3">
                            <label htmlFor="price" className="form-label">Precio:</label>
                            <input type="number"  value={price} onChange={this.cambioValor} className="form-control" id="price" name="price" aria-describedby="precio" placeholder="price"/>
                        </div>

                        <div className="bnt-group" role="group">
                            <button className="btn btn-success" type="submit">guardar</button>
                            <button className="btn btn-danger" type="reset">cancelar</button>
                        </div>
                    </form>
                </div>
            </div >
        );
    }
}

export default withRouter(Editar);