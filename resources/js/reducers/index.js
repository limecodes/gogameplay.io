import { combineReducers } from 'redux';
import visitorReducer from './visitorReducer';

export default combineReducers({
	visitor: visitorReducer
});