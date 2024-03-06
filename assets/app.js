import React, { useState } from 'react';
import {createRoot} from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';

// any CSS you import will output into a single css file (app.css in this case)
import './styles/index.scss';
import Homepage from './pages/Homepage';
import {LoginPage} from './pages/LoginPage';
import authApi from './services/authApi';
import AuthContext from './context/AuthContext';
import { RegisterPage } from './pages/RegisterPage';
import { PersonalDashboard } from './pages/PersonalDashboard';
import PrivateRoute from './routing/PrivateRoute';
import { AdvertissementPage } from './pages/AdvertissementPage';
import { AdDescriptionPage } from './pages/AdDescriptionPage';
import Contact from './pages/Contact';


authApi.setup();
const App = () => {
    const [isAuthenticated, setIsAuthenticated] = useState(authApi.isAuthenticated());

    return (
        <AuthContext.Provider value={{
              isAuthenticated,
              setIsAuthenticated
            }}>
                
            <BrowserRouter>
                <Routes>
                    <Route path="/mon-espace" element={<PrivateRoute /> } >
                        <Route path="/mon-espace" element={<PersonalDashboard />} />
                    </Route>
                    <Route path="/annonces" element={<PrivateRoute /> } >
                        <Route path="/annonces" element={<AdvertissementPage />} />
                    </Route> 
                    <Route path='/annonce/:id' element={<PrivateRoute /> } >
                        <Route path='/annonce/:id' element={<AdDescriptionPage />} />
                    </Route>    
                    <Route path="/" element={<Homepage />} />
                    <Route path="/login"  element={<LoginPage />} />
                    <Route path="/inscription"  element={<RegisterPage />} />
                    <Route path="/contact" element={<Contact />} />
                    
                </Routes>
            </BrowserRouter>
        </AuthContext.Provider>
    );
};
export default App;
const container = document.getElementById('app');
const root = createRoot(container);
root.render(<App />);