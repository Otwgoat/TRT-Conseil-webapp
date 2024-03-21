import React, { useEffect, useState } from "react";
import userApi from "../../../services/userApi";
import { initializeApp } from "firebase/app";
import { getStorage, ref } from "firebase/storage";
import "firebase/storage";

export const GetCurriculum = () => {
  const [currentUser, setCurrentUser] = useState({});
  const [httpsReference, setHttpsReference] = useState("");
  const firebaseConfig = () => {
    if (process.env.NODE_ENV === "development") {
      return {
        apiKey: process.env.REACT_APP_FIREBASE_API_KEY,
        authDomain: process.env.REACT_APP_FIREBASE_AUTH_DOMAIN,
        projectId: process.env.REACT_APP_FIREBASE_PROJECT_ID,
        storageBucket: process.env.REACT_APP_FIREBASE_STORAGE_BUCKET,
        messagingSenderId: process.env.REACT_APP_FIREBASE_MESSAGING_SENDER_ID,
        appId: process.env.REACT_APP_FIREBASE_APP_ID,
      };
    } else {
      return {
        apiKey: REACT_APP_FIREBASE_API_KEY,
        authDomain: REACT_APP_FIREBASE_AUTH_DOMAIN,
        projectId: REACT_APP_FIREBASE_PROJECT_ID,
        storageBucket: REACT_APP_FIREBASE_STORAGE_BUCKET,
        messagingSenderId: REACT_APP_FIREBASE_MESSAGING_SENDER_ID,
        appId: REACT_APP_FIREBASE_APP_ID,
      };
    }
  };

  const app = initializeApp(firebaseConfig);
  const storage = getStorage(app);
  useEffect(() => {
    const fetchData = async () => {
      const data = await userApi.getUser();
      setCurrentUser(data);

      console.log(data);
    };
    fetchData();
  }, []);

  useEffect(() => {
    setHttpsReference(ref(getStorage(), currentUser.cvPath));
    console.log(httpsReference);
  }, [currentUser]);

  return (
    <div className="dashboardContent">
      <h2>Mon CV</h2>
      {!currentUser.cvPath && <p>Vous n'avez pas encore importé de CV.</p>}
      {currentUser.cvPath && (
        <div className="contentItem">
          <embed
            src={currentUser.cvPath}
            type="application/pdf"
            width="100%"
            height="600px"
          />
        </div>
      )}
    </div>
  );
};
