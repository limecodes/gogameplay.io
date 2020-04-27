import React from 'react';
import { createStore, applyMiddleware } from 'redux';
import thunk from 'redux-thunk';
import { composeWithDevTools } from 'redux-devtools-extension';
import reducers from '../reducers';

const initialState = {};

const middleware = [thunk];

const enhancers = composeWithDevTools(
	applyMiddleware(...middleware)
);

export const store = createStore(reducers, initialState, enhancers);