import React, { useEffect, useState } from "react";
import userApi from "../../../services/userApi";
import { initializeApp } from "firebase/app";
import { getStorage, ref } from "firebase/storage";
import "firebase/storage";

export const GetCurriculum = () => {
  const [currentUser, setCurrentUser] = useState({});
  const [httpsReference, setHttpsReference] = useState("");
  const firebaseConfig = {
    apiKey: process.env.FIREBASE_API_KEY,
    authDomain: process.env.FIREBASE_AUTH_DOMAIN,
    projectId: process.env.FIREBASE_PROJECT_ID,
    storageBucket: process.env.FIREBASE_STORAGE_BUCKET,
    messagingSenderId: process.env.FIREBASE_MESSAGING_SENDER_ID,
    appId: process.env.FIREBASE_APP_ID,
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
