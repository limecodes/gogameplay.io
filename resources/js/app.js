import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { Provider } from 'react-redux';
import { PersistGate } from 'redux-persist/integration/react';

import { store, persistor } from './store';

import RootComponent from './components/RootComponent';

export default class App extends Component {

	render() {
		return (
			<Provider store={ store }>
				<PersistGate persistor={ persistor }>
	        		<RootComponent uid={ this.props.uid } device={ this.props.device } connection={ this.props.connection } carrier={ this.props.carrier } />
	        	</PersistGate>
	        </Provider>
    	);
	}
}

if (document.getElementById('app')) {
	var elem = document.getElementById('app');
	var uid = elem.getAttribute('data-uid');
	var device = elem.getAttribute('data-device');
	var connection = elem.getAttribute('data-connection');
	var carrier = elem.getAttribute('data-carrier')

    ReactDOM.render(<App uid={ uid } device={ device } connection={ connection } carrier={ carrier } />, elem);
}
