import React from 'react';
import { createStore, applyMiddleware } from 'redux';
import thunk from 'redux-thunk';
import { composeWithDevTools } from 'redux-devtools-extension';
import { persistStore, persistReducer } from 'redux-persist';
import { createWhitelistFilter } from 'redux-persist-transform-filter';
import autoMergeLevel2 from 'redux-persist/lib/stateReconciler/autoMergeLevel2';
import storage from 'redux-persist/lib/storage';

import reducers from '../reducers';

const persistConfig = {
	key: 'root',
	storage: storage,
	stateReconciler: autoMergeLevel2,
	whitelist: ['validation', 'carriers']
};

const initialState = {};

const middleware = [thunk];

const enhancers = composeWithDevTools(
	applyMiddleware(...middleware)
);

const persistedReducer = persistReducer(persistConfig, reducers);

export const store = createStore(persistedReducer, initialState, enhancers);
export const persistor = persistStore(store);