import { combineReducers } from 'redux';
import visitorReducer from './visitorReducer';
import validationReducer from './validationReducer';

export default combineReducers({
	visitor: visitorReducer,
	validation: validationReducer
});