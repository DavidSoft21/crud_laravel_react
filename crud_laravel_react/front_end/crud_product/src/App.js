//import logo from './logo.svg';
import './App.css';
import Listar from './componentes/listar';
import Crear from './componentes/crear';
import Editar from './componentes/editar';
import { Route, Routes, BrowserRouter as Router,Link } from 'react-router-dom';
function App() {
  return (
    <Router>
      <nav className="navbar navbar-expand navbar-light bg-light">
        <div className="nav navbar-nav">
          <Link className="nav-item nav-link active" to={"/"}><span className="sr-only">Sistema</span> Listar </Link>
          <Link className="nav-item nav-link" to={"/crear"}>Crear</Link>
        </div>
      </nav>
      <hr/>
      <div className="container">
        <Routes>
          <Route exact path="/" element={<Listar/>}></Route>
          <Route exact path="/crear" element={<Crear/>}></Route>
          <Route exact path="/editar/:id" element={<Editar/>}></Route>
        </Routes>
      </div>
    </Router>
  );
}

export default App;
