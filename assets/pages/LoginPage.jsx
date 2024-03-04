import React, { useState, useContext, useEffect } from "react";
import { Header } from "../components/Header";
import authApi from "../services/authApi";
import AuthContext from "../context/AuthContext";
import { useNavigate } from "react-router-dom";
import userApi from "../services/userApi";

export const LoginPage = () => {
  const navigate = useNavigate();
  const [error, setError] = useState(" ");
  const [credentials, setCredentials] = useState({
    username: "",
    password: "",
  });
  const { isAuthenticated: auth, setIsAuthenticated } = useContext(AuthContext);
  const [isAuthenticated, setIsAuth] = useState(auth);
  const [user, setUser] = useState({});

  // === manage fields ===

  const handleChange = ({ currentTarget }) => {
    const { value, name } = currentTarget;
    setCredentials({ ...credentials, [name]: value });
  };
  // === manage submit ===
  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await authApi.authenticate(credentials);
      setError("");
      setIsAuthenticated(true);
      navigate("/mon-espace");
    } catch (error) {
      setError(
        "Aucun compte ne possède cette adresse email, ou alors les informations ne correspondent pas."
      );
    }
  };

  return (
    <div className="container">
      <Header />
      <main id="loginPage">
        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label htmlFor="username">Adresse email</label>
            <input
              value={credentials.username}
              onChange={handleChange}
              type="email"
              placeholder="Adresse email de connexion"
              name="username"
              className="form-control"
              id="username"
            />
          </div>
          <div className="form-group">
            <label htmlFor="password">Mot de passe</label>
            <input
              value={credentials.password}
              onChange={handleChange}
              type="password"
              placeholder="Mot de passe"
              name="password"
              className="form-control"
              id="password"
            />
          </div>
          {error && <p className="errorMessage">{error}</p>}
          <div className="form-group">
            <button className="ctaButton" type="submit">
              Je me connecte
            </button>
          </div>
        </form>
      </main>
    </div>
  );
};
