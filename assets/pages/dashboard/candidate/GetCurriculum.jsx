import React, { useEffect, useState } from "react";
import userApi from "../../../services/userApi";
import { initializeApp } from "firebase/app";
import { getStorage, ref } from "firebase/storage";
import "firebase/storage";

export const GetCurriculum = () => {
  const [currentUser, setCurrentUser] = useState({});
  const [httpsReference, setHttpsReference] = useState("");
  const [firebaseConfig, setFirebaseConfig] = useState({});

  const assignApiKey = () => {
    if (process.env.NODE_ENV === "development") {
      return process.env.REACT_APP_FIREBASE_API_KEY;
    } else {
      return REACT_APP_FIREBASE_API_KEY;
    }
  };
  const [apiKey, setApiKey] = useState(assignApiKey);
  const assignAuthDomain = () => {
    if (process.env.NODE_ENV === "development") {
      return process.env.REACT_APP_FIREBASE_AUTH_DOMAIN;
    } else {
      return REACT_APP_FIREBASE_AUTH_DOMAIN;
    }
  };
  const [authDomain, setAuthDomain] = useState(assignAuthDomain);
  const assignProjectId = () => {
    if (process.env.NODE_ENV === "development") {
      return process.env.REACT_APP_FIREBASE_PROJECT_ID;
    } else {
      return REACT_APP_FIREBASE_PROJECT_ID;
    }
  };
  const [projectId, setProjectId] = useState(assignProjectId);
  const assignStorageBucket = () => {
    if (process.env.NODE_ENV === "development") {
      return process.env.REACT_APP_FIREBASE_STORAGE_BUCKET;
    } else {
      return REACT_APP_FIREBASE_STORAGE_BUCKET;
    }
  };
  const [storageBucket, setStorageBucket] = useState(assignStorageBucket);
  const assignMessagingSenderId = () => {
    if (process.env.NODE_ENV === "development") {
      return process.env.REACT_APP_FIREBASE_MESSAGING_SENDER_ID;
    } else {
      return REACT_APP_FIREBASE_MESSAGING_SENDER_ID;
    }
  };
  const [messagingSenderId, setMessagingSenderId] = useState(
    assignMessagingSenderId
  );
  const assignAppId = () => {
    if (process.env.NODE_ENV === "development") {
      return process.env.REACT_APP_FIREBASE_APP_ID;
    } else {
      return REACT_APP_FIREBASE_APP_ID;
    }
  };
  const [appId, setAppId] = useState(assignAppId);
  setFirebaseConfig({
    apiKey: apiKey,
    authDomain: authDomain,
    projectId: projectId,
    storageBucket: storageBucket,
    messagingSenderId: messagingSenderId,
    appId: appId,
  });
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
