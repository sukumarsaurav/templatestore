// Firebase configuration for TemplateHub
const firebaseConfig = {
  apiKey: "YOUR_API_KEY",
  authDomain: "YOUR_PROJECT_ID.firebaseapp.com",
  projectId: "YOUR_PROJECT_ID",
  storageBucket: "YOUR_PROJECT_ID.appspot.com",
  messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
  appId: "YOUR_APP_ID"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Auth reference
const auth = firebase.auth();
const db = firebase.firestore();

// Set persistence to local to persist auth state across page refreshes
auth.setPersistence(firebase.auth.Auth.Persistence.LOCAL);

// Helper function to get the current user
function getCurrentUser() {
  return new Promise((resolve, reject) => {
    const unsubscribe = auth.onAuthStateChanged(user => {
      unsubscribe();
      resolve(user);
    }, reject);
  });
}

// Function to handle the login form submission
function handleLogin(email, password) {
  return auth.signInWithEmailAndPassword(email, password)
    .then((userCredential) => {
      // User signed in
      const user = userCredential.user;
      
      // Save user data to session storage
      sessionStorage.setItem('user_id', user.uid);
      sessionStorage.setItem('user_email', user.email);
      sessionStorage.setItem('user_name', user.displayName || email.split('@')[0]);
      
      return user;
    });
}

// Function to handle the registration form submission
function handleRegistration(email, password, name) {
  return auth.createUserWithEmailAndPassword(email, password)
    .then((userCredential) => {
      // User created
      const user = userCredential.user;
      
      // Update the user profile with the name
      return user.updateProfile({
        displayName: name
      }).then(() => {
        // Save user data to session storage
        sessionStorage.setItem('user_id', user.uid);
        sessionStorage.setItem('user_email', user.email);
        sessionStorage.setItem('user_name', name);
        
        // Save additional user data to Firestore
        return db.collection('users').doc(user.uid).set({
          name: name,
          email: email,
          createdAt: firebase.firestore.FieldValue.serverTimestamp(),
          preferredCurrency: 'USD',
          preferredLanguage: 'en'
        });
      });
    });
}

// Function to handle password reset
function resetPassword(email) {
  return auth.sendPasswordResetEmail(email);
}

// Function to handle logout
function handleLogout() {
  // Clear session storage
  sessionStorage.removeItem('user_id');
  sessionStorage.removeItem('user_email');
  sessionStorage.removeItem('user_name');
  
  // Sign out from Firebase
  return auth.signOut();
}

// Function to check if user is logged in
function isUserLoggedIn() {
  return !!sessionStorage.getItem('user_id');
}

// Functions for currency and language preferences
async function getUserPreferences() {
  const userId = sessionStorage.getItem('user_id');
  
  if (userId) {
    try {
      const doc = await db.collection('users').doc(userId).get();
      if (doc.exists) {
        return doc.data();
      }
    } catch (error) {
      console.error("Error getting user preferences:", error);
    }
  }
  
  // Default preferences if not logged in or error
  return {
    preferredCurrency: localStorage.getItem('currency') || 'USD',
    preferredLanguage: localStorage.getItem('language') || 'en'
  };
}

async function updateUserPreferences(preferences) {
  const userId = sessionStorage.getItem('user_id');
  
  // Save to localStorage for non-logged in users
  if (preferences.preferredCurrency) {
    localStorage.setItem('currency', preferences.preferredCurrency);
  }
  
  if (preferences.preferredLanguage) {
    localStorage.setItem('language', preferences.preferredLanguage);
  }
  
  // Save to Firestore for logged in users
  if (userId) {
    try {
      await db.collection('users').doc(userId).update(preferences);
    } catch (error) {
      console.error("Error updating user preferences:", error);
    }
  }
}

// Google Sign-In function
function signInWithGoogle() {
  const provider = new firebase.auth.GoogleAuthProvider();
  return auth.signInWithPopup(provider)
    .then((result) => {
      const user = result.user;
      
      // Save user data to session storage
      sessionStorage.setItem('user_id', user.uid);
      sessionStorage.setItem('user_email', user.email);
      sessionStorage.setItem('user_name', user.displayName || user.email.split('@')[0]);
      
      // Check if this is a new user
      return db.collection('users').doc(user.uid).get().then((doc) => {
        if (!doc.exists) {
          // New user, save data to Firestore
          return db.collection('users').doc(user.uid).set({
            name: user.displayName || user.email.split('@')[0],
            email: user.email,
            createdAt: firebase.firestore.FieldValue.serverTimestamp(),
            preferredCurrency: 'USD',
            preferredLanguage: 'en'
          });
        }
      });
    });
}

// Facebook Sign-In function
function signInWithFacebook() {
  const provider = new firebase.auth.FacebookAuthProvider();
  return auth.signInWithPopup(provider)
    .then((result) => {
      const user = result.user;
      
      // Save user data to session storage
      sessionStorage.setItem('user_id', user.uid);
      sessionStorage.setItem('user_email', user.email);
      sessionStorage.setItem('user_name', user.displayName || user.email.split('@')[0]);
      
      // Check if this is a new user
      return db.collection('users').doc(user.uid).get().then((doc) => {
        if (!doc.exists) {
          // New user, save data to Firestore
          return db.collection('users').doc(user.uid).set({
            name: user.displayName || user.email.split('@')[0],
            email: user.email,
            createdAt: firebase.firestore.FieldValue.serverTimestamp(),
            preferredCurrency: 'USD',
            preferredLanguage: 'en'
          });
        }
      });
    });
} 