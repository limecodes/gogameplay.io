import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class App extends Component {

	componentDidMount() {
		console.log('mounted', this.props);
		console.log('navigator', navigator);
	}

	render() {
		return (
	        <div className="container">
	            <div className="row justify-content-center">
	                <div className="col-md-8">
	                    <div className="card">
	                        <div className="card-header">Example Component</div>

	                        <div className="card-body">I'm an example component!</div>
	                    </div>
	                </div>
	            </div>
	        </div>
    	);
	}
}

if (document.getElementById('app')) {
	var elem = document.getElementById('app');
	var uid = elem.getAttribute('data-uid');

    ReactDOM.render(<App uid={ uid } />, elem);
}
